<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Circulation\DistributionPlanDetail as DistPlanDet;
use App\Masterdata\Agent;

class DistributionPlanDetController extends Controller {

    /**
     * DistDetails rules for creating new details
     */
    protected $rules = [
        'agent_id'=>'required|numeric',
        'quota'=>'numeric',
        'consigned'=>'numeric',
        'gratis'=>'numeric'
        ];

    /**
     * DistDetails rules for updating existing details does not need
     * `agent_id` and required
     */
    protected $rulesUpdate = [
        'quota'=>'required|numeric',
        'consigned'=>'required|numeric',
        'gratis'=>'required|numeric'
        ];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($distPlanID)
	{
		//To remove duplicate, get agent NOT listed in $distPlanID
        $addedAgents = DistPlanDet::with('agent')
            ->where('distribution_plan_id', '=', $distPlanID)->get();
        //Convert to array
        $agents = [];
        foreach($addedAgents as $detail) {
            $agents[] = $detail->agent->id;
        }
        $unAssignedAgent = Agent::select('id','name')
            ->whereNotIn('id', $agents)->get();
        return view('circulation/distribution-plan-details-form',
            ['agents'=>$unAssignedAgent,
             'distPlanID'=>$distPlanID
            ]
       );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($distPlanID, Request $request)
	{
        $this->validate($request, $this->rules);
        $input = $request->only(
            'agent_id', 
            'consigned',
            'quota',
            'gratis');
        $input['distribution_plan_id'] = $distPlanID;
        $details = DistPlanDet::firstOrCreate($input);
        $msg = "Done! Added new agent";
        return redirect("circulation/distribution-plan/$distPlanID")->with('message', $msg);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($distPlanID, $detailsID)
	{
        $detail = DistPlanDet::with('agent', 'distributionPlan.edition.magazine')->find($detailsID);
        return view('circulation/distribution-plan-details-show',
            ['detail'=>$detail,
             'distPlanID'=>$distPlanID
            ]
        );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($distPlanID, $detailsID)
	{

        //Agents should be 'locked' therefore not editable
        $details = DistPlanDet::with('agent', 'distributionPlan.edition.magazine')->find($detailsID);
        return view('circulation/distribution-plan-details-form',
            ['detail'=>$details,
             'distPlanID'=>$distPlanID,
             'details_id'=>$detailsID,
             'method'=>'PUT'
            ]
        );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($distPlanID, $detailsID, Request $request)
	{
        $this->validate($request, $this->rulesUpdate);
        $input = $request->only('consigned', 'quota', 'gratis');
        $detail = DistPlanDet::find($detailsID);
        $detail->consigned=$input['consigned'];
        $detail->quota=$input['quota'];
        $detail->gratis=$input['gratis'];
        $detail->save();
        $msg = "Update successful!";
        return redirect("circulation/distribution-plan/$distPlanID")->with('message', $msg);

		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($distPlanID, $detailsID)
	{
	    try{
            $detail = DistPlanDet::findOrFail($detailsID);
            $detail->delete();
            $execMsg = "Delete successful!";
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot delete record. Data not found.";
            return redirect("circulation/distribution-plan/$distPlanID")->with('errMsg',$execMsg);
        }
        return redirect("circulation/distribution-plan/$distPlanID")->with('message',$execMsg);
	}
}
