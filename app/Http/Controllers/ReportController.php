<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Masterdata\Agent;
use App\Masterdata\Magazine;
use App\Masterdata\Edition;
use App\Circulation\DistributionPlan as
    DistPlan;
use App\Circulation\DistributionPlanDetail as
    DistPlanDet;
use App\Circulation\DistributionRealization as
    DistRealization;
use App\Circulation\DistributionRealizationDetail as
    DistRealDet;

class ReportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('report/report-dashboard');
	}

    /**
     * Return form view to create report
     *
     * @return Response
     */
    public function getCreateDistRealization()
    {
        $magazines = Magazine::all();
        $editions = Edition::with('magazine')
            ->orderBy('magazine_id')
            ->get();
        return view('report/form-dist-realization',
            [
                'magazines'=>$magazines,
                'editions'=>$editions
            ]
        );
    }

    /**
     * Process form request to create report
     *
     * This will generate report for distribution
     * realization.
     *
     * @param Request $request
     * @return Response
     */
    public function postDistRealization(Request $request)
    {
        $this->validate($request, ['edition_id'=>'required|numeric']);

        /**
        // Prepare data for report
        $DistPlan = DistPlan::with('details.agent')
            ->where('edition_id', '=', $request->edition_id)
            ->first();

        $DistReal = DistRealization::with('details.agent')
            ->where('edition_id', '=', $request->edition_id)
            ->first();
         */

        $DistPlan = DistPlan::where('edition_id', '=', $request->edition_id)->first();
        $DistReal = DistRealization::where('edition_id', '=', $request->edition_id)->first();
        // if empty, don't render any result
        if(!$DistPlan or !$DistReal) {
            $msg = "Tidak ditemukan perencanaan atau realisasi";
            return redirect('report/create-dist-realization')->with('errMsg', $msg);
        }

        $DistPlanDet = DistPlanDet::with('agent.agent_category')
            ->where('distribution_plan_id', '=', $DistPlan->id)
            ->get();
        $DistRealDet = DistRealDet::with('agent.agent_category')
            ->where('distribution_realization_id', '=', $DistReal->id)
            ->get();

        // Change structure so that $DistPlanDet
        // and $DistRealDet are keyed by agent_id
        $agent_DistPlanDet = $DistPlanDet->keyBy('agent_id');
        $agent_DistRealDet = $DistRealDet->keyBy('agent_id');

        // Get agent details
        $keys = $agent_DistPlanDet->keys();
        $agents = Agent::whereIn('id', $keys->all())->get();

        // Now, return agents aggregat
        return view('report/preview-dist-realization',
            [
                'distPlanDet'=>$agent_DistPlanDet,
                'distRealDet'=>$agent_DistRealDet,
                'agents'=>$agents
            ]
        );




    }


}
