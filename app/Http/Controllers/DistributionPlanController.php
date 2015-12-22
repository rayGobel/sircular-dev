<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;
use App\Circulation\DistributionPlan as DistPlan;
use App\Circulation\DistributionPlanDetail as DistPlanDet;

//Use models
use App\Masterdata\Magazine;
use App\Masterdata\Edition;

class DistributionPlanController extends Controller {

    /**
     * New plan rules
     */
    protected $distPlan_rules = [
        'magazine_id'=>'required|numeric',
        'edition_code'=>'required',
        'print'=>'required|numeric',
        'gratis'=>'required|numeric',
        'distributed'=>'required|numeric',
        'stock'=>'required|numeric',
        'publish_date'=>'required|date_format:d-m-Y',
        'print_number'=>'required|numeric'
        ];

    protected $distPlanStore_rules = [
        'print'=>'required|numeric',
        'gratis'=>'required|numeric',
        'distributed'=>'required|numeric',
        'stock'=>'required|numeric',
        'publish_date'=>'required|date_format:d-m-Y',
        'print_number'=>'required|numeric'
        ];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $dist_plans = DistPlan::with('edition.magazine')->paginate(10);
        $dist_plans->setPath('');
        return view('circulation/distribution-plan-table',
            ['dist_plans'=>$dist_plans]);
	}

	/**
	 * Show the form for creating a new resource.
     *
     * On create, fetch magazine list.
	 *
	 * @return Response
	 */
	public function create()
	{
        //Distribution plan list
        $distPlans = DistPlan::with('edition.magazine')->get();
        //Magazine list
        $magList = Magazine::all();
        return view('circulation/distribution-plan-form',
            ['magList'=>$magList,
             'dist_plans'=>$distPlans
        ]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, $this->distPlan_rules);
        //We will request edition_id first from edition_code
        //then, look for valid id before inserting to plan
        //
        //I should think about duplicate data here. (Duplicate plans)
        $code = $request->only('edition_code');

        try {

            $edition = Edition::where('edition_code', '=', $code['edition_code'])
                ->where('magazine_id', '=', $request->input('magazine_id'))
                ->firstOrFail();

        } catch (ModelNotFoundException $e) {

            $eMsg = "Edition not found.";
            return redirect('circulation/distribution-plan/create')->with('errMsg', $eMsg);

        }
        //Begin storing information
        $input = $request->only(
                                'print',
                                'gratis',
                                'distributed',
                                'stock',
                                'publish_date',
                                'print_number'
                            );
        $input['edition_id'] = $edition->id;
        //Convert date to mysql date format
        $pbDate = strtotime($input['publish_date']);
        $input['publish_date'] = date('Y-m-d', $pbDate);
        //Insert to database
        $distPlan = DistPlan::firstOrCreate($input);
        $msg = "Added new Plan!";
        return redirect('circulation/distribution-plan')->with('message', $msg);

	}

    /**
     * Process store request based on previous distribution plan
     *
     * @param  Request $request
     * @return Response
     */
    public function postCreateFromPrev(Request $request)
    {
        $this->validate($request, [
            'dist_plan_id'=>'required|numeric',
            'magazine_id'=>'required|numeric',
            'edition_code'=>'required'
        ]);

        // Go through validation steps
        try {
            // Check if edition code exist or distribution plan for previous edition exist
            $edition = Edition::where('edition_code', '=', $request->input('edition_code'))
                ->where('magazine_id', '=', $request->input('magazine_id'))
                ->firstOrFail();
            $distPlan = DistPlan::with('details')->findOrFail($request->input('dist_plan_id'));
            // Check if distribution plan is already exist or not
            $existing = DistPlan::where('edition_id', '=', $edition->id)->first();
            if ($existing) {
                throw new \Exception("Distribution plan has already been made!");
            }

        } catch (ModelNotFoundException $e) {
            $execMsg = "Distribution plan is not found! / Edition Code mismatch!";
            return redirect('circulation/distribution-plan')->with('errMsg', $execMsg);
        } catch (\Exception $e) {
            return redirect('circulation/distribution-plan')->with('errMsg', $e->getMessage());
        }


        // Create new distribution. Unfilled value will be
        // set to default. Date will be set to today (Please
        // check php timezone for possible date mismatch)
        $distPlanNew = new DistPlan;
        $distPlanNew->edition_id = $edition->id;
        $distPlanNew->print = $distPlan->print;
        $distPlanNew->gratis = $distPlan->gratis;
        $distPlanNew->distributed = $distPlan->distributed;
        $distPlanNew->stock = $distPlan->stock;
        $distPlanNew->publish_date = date('Y-m-d');
        $distPlanNew->print_number = 1;
        $distPlanNew->save();

        // generate exact data for dist_realize_details from dist_plan_details
        foreach($distPlan->details as $distPlanDet) {
            $distPlanDetNew = new DistPlanDet;
            $distPlanDetNew->distribution_plan_id = $distPlanNew->id;
            $distPlanDetNew->agent_id = $distPlanDet->agent_id;
            $distPlanDetNew->consigned = $distPlanDet->consigned;
            $distPlanDetNew->gratis = $distPlanDet->gratis;
            $distPlanDetNew->quota = $distPlanDet->quota;
            $distPlanDetNew->save();

        }

        $msg = "Done! New distribution plan with id={$distPlanNew->id}";
        return redirect('circulation/distribution-plan')->with('message', $msg);

    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $dist = DistPlan::with('details.agent', 'edition.magazine')->find($id);
        return view('circulation/distribution-plan-details',
            ['dist'=>$dist, 'dist_id'=>$id]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $magz = Magazine::all();
        $distPlan = DistPlan::with('edition.magazine')->find($id);
        return view('circulation/distribution-plan-form',
            ['distPlan'=>$distPlan,
            'distID'=>$id,
            'magList'=>$magz,
            'method'=>'PUT'
            ]);
	}

	/**
	 * Update the specified resource in storage.
     *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$this->validate($request, $this->distPlanStore_rules);
        $ip = $request->only(
                             'print',
                             'gratis',
                             'distributed',
                             'stock',
                             'publish_date',
                             'print_number');

        $distPlan = DistPlan::find($id);
        $distPlan->print = $ip['print'];
        $distPlan->gratis = $ip['gratis'];
        $distPlan->distributed = $ip['distributed'];
        $distPlan->stock = $ip['stock'];
        #Convert time as always
        $pbDate = strtotime($ip['publish_date']);
        $distPlan->publish_date = date('Y-m-d', $pbDate);
        $distPlan->print_number = $ip['print_number'];
        $distPlan->save();
        $msg = 'Update successful!';
        return redirect('circulation/distribution-plan')->with('message', $msg);
	}

	/**
	 * Remove the specified resource from storage.
     *
     * Deletion will also remove all of its relationships
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {
            $distPlan = DistPlan::with('details')->findOrFail($id);
            //Ok? Destroy with relationships
            $distPlan->delete();
            $execMsg = "Delete successful!";
        } catch (ModelNotFoundException $e) {
            //In case of failure on deletion/finding data, set errMsg
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('circulation/distribution-plan')->with('errMsg',$execMsg);
        }
        return redirect('circulation/distribution-plan')->with('message',$execMsg);
	}

}
