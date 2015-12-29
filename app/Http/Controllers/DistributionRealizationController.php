<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;
use App\Circulation\DistributionPlan as DistPlan;
use App\Circulation\DistributionRealization as DistRealize;
use App\Circulation\DistributionRealizationDetail as DistRealizeDetail;

class DistributionRealizationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $dist_reals = DistRealize::with('edition.magazine')->paginate(10);
        $dist_reals->setPath('');
        return view('circulation/distribution-realization-table',
            ['dist_reals'=>$dist_reals]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //Distribution Plan list
        $dist_plans = DistPlan::with('edition.magazine')
            ->where('is_realized', '=', 0)
            ->get();
        return view('circulation/distribution-realization-form',
            ['dist_plans'=>$dist_plans]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, ['dist_plan_id'=>'required|numeric']);

        $distPlanID = $request->input('dist_plan_id');
        // Get distPlan
        try {
            // validate if dist_plan is already realized 
            $distPlan = DistPlan::with('details')
                ->where('is_realized', '=', 0)
                ->findOrFail($distPlanID);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Distribution plan is not found or has been realized.";
            return redirect('circulation/distribution-realization')->with('errMsg', $execMsg);
        }

        // The big work starts here
        // dist_plan exist, and not yet realized,
        // generate exact data for dist_realize from dist_plan (manually assign)
        $distReal = new DistRealize;
        $distReal->distribution_plan_id = $distPlanID;
        $distReal->edition_id = $distPlan->edition_id;
        $distReal->print = $distPlan->print;
        $distReal->gratis = $distPlan->gratis;
        $distReal->distributed = $distPlan->distributed;
        $distReal->stock = $distPlan->stock;
        $distReal->publish_date = $distPlan->publish_date;
        $distReal->print_number = $distPlan->print_number;
        $distReal->save();

        // make dist_plan.is_realized set to 1
        $distPlan->is_realized = 1;
        $distPlan->save();
        // generate exact data for dist_realize_details from dist_plan_details
        foreach($distPlan->details as $distPlanDet) {
            $distRealDet = new DistRealizeDetail;
            $distRealDet->distribution_realization_id = $distReal->id;
            $distRealDet->agent_id = $distPlanDet->agent_id;
            $distRealDet->save();

        }
        // done
        return redirect('circulation/distribution-realization');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $dist = DistRealize::with('details.agent', 'edition.magazine')->find($id);
        return view('circulation/distribution-realization-details',
            ['dist'=>$dist, 'dist_id'=>$id]);
	}

    /**
     * Show comparisons between plan and realization
     *
     * @param int $distRealizationID
     * @param int $distPlanID
     * @return Response
     */
    public function compare($distRealizationID, $distPlanID)
    {
        $dist_real = DistRealize::with('details.agent')->find($distRealizationID);
        // Check if distPlanID match with $distRealizationID
        if ($dist_real->distribution_plan_id != $distPlanID) {
            return redirect()->back()->with('errMsg', 'Mismatched IDs!');
        }

        $dist_plan = DistPlan::with('details.agent')->find($distPlanID);
        // Try to combine both of them based on agent_id
        $agent_plan = $dist_plan->details->keyBy('agent_id');
        $agent_real = $dist_real->details->keyBy('agent_id');
        return view('circulation/distribution-realization-compare',
            ['agent_plan'=>$agent_plan, 'agent_real'=>$agent_real]);

    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $msg = "Distribution realization is uneditable!";
		return redirect('circulation/distribution-realization')->with('errMsg', $msg);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
