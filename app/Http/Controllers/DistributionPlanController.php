<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;
use App\Circulation\DistributionPlan as DistPlan;

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
        'percent_fee'=>'required',
        'value_fee'=>'required',
        'print'=>'required|numeric',
        'gratis'=>'required|numeric',
        'distributed'=>'required|numeric',
        'stock'=>'required|numeric',
        'publish_date'=>'required|date_format:d-m-Y',
        'print_number'=>'required|numeric'
        ];

    protected $distPlanStore_rules = [
        'percent_fee'=>'required',
        'value_fee'=>'required',
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
        //Magazine list
        $magList = Magazine::all();
        return view('circulation/distribution-plan-form',
                    ['magList'=>$magList]);
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

            $edition = Edition::where('edition_code', '=', $code['edition_code'])->firstOrFail();

        } catch (ModelNotFoundException $e) {

            $eMsg = "Edition not found.";
            return redirect('circulation/distribution-plan/create')->with('errMsg', $eMsg);

        }
        //Begin storing information
        $input = $request->only('percent_fee',
                                'value_fee',
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
        $ip = $request->only('percent_fee',
                             'value_fee',
                             'print',
                             'gratis',
                             'distributed',
                             'stock',
                             'publish_date',
                             'print_number');

        $distPlan = DistPlan::find($id);
        $distPlan->percent_fee = $ip['percent_fee'];
        $distPlan->value_fee = $ip['value_fee'];
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
