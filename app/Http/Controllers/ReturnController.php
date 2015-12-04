<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;
use Illuminate\Contracts\Validation\ValidationException
    as ValidationException;

use App\Circulation\DistributionPlanDetail
    as DistPlanDet;
use App\Circulation\DistributionPlan
    as DistPlan;

use App\Circulation\ReturnItem;
use App\Circulation\Delivery;
use App\Masterdata\Agent;
use App\Masterdata\Edition;

class ReturnController extends Controller {

    /**
     * New return rules
     */
    protected $rules = [
        'agent_id' => 'numeric|required',
        'edition_id'=> 'numeric|required',
        'total'=>'numeric|required',
        'date'=>'required'
        ];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $returnList = ReturnItem::with('distPlanDet.agent',
                                   'distPlanDet.distributionPlan.edition.magazine')
                                   ->paginate(10);

        $returnList->setPath('');
        return view('circulation/return-list',
            ['returnList'=>$returnList]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $agents = Agent::all();
        $editions = Edition::with('magazine')->get();

        return view('circulation/return-form',
            ['agents'=>$agents,
             'editions'=>$editions
            ]
        );

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, $this->rules);
        $input = $request->only('agent_id', 'edition_id', 'total', 'date');
        $issueDate = strtotime($input['date']);
        $input['date'] = date('Y-m-d', $issueDate);
        //Validate if there are any Distribution plan
        try {
            $distPlanDetID = $this->validateReturnItem($input);
            $this->validateReturnDate($input);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        } catch (ModelNotFoundException $e) {
            // If we can't find data
            return redirect()->back()->withErrors('No deliveries were made!');
        }
        //Compose what to enter to database
        $ed = Edition::with('magazine')->find($input['edition_id']);
        $toDB['dist_plan_det_id'] = $distPlanDetID;
        $toDB['agent_id'] = $input['agent_id'];
        $toDB['edition_id'] = $input['edition_id'];
        $toDB['magazine_id'] = $ed->magazine->id;
        $toDB['date'] = $input['date'];
        $toDB['total'] = $input['total'];
        $num = ReturnItem::max('num')+1;
        $toDB['num'] = $num;
        $toDB['number'] = "{$input['edition_id']}/".str_pad($num, 6, 0, STR_PAD_LEFT);

        $newReturn = ReturnItem::firstOrCreate($toDB);
        $msg = "Done! New Return # : {$newReturn->number}";
        return redirect("circulation/return")->with('message', $msg);
	}

    /**
     * return true if item are returnable
     *
     * Return criterias are:
     *  - sum is less than or equal to total delivered item
     *
     *  @return int distPlanDetID if passed all exceptions
     */
    private function validateReturnItem($input)
    {
        $planDet = DistPlanDet::with('distributionPlan')->where('agent_id','=',$input['agent_id'])->get();
        if ($planDet->isEmpty()) {
            $err = 'No delivery was made for this agent';
            throw new \Exception($err);
        }

        $distPlanDetID = 0;
        foreach($planDet as $pd) {
            if ($pd->distributionPlan->edition_id == (int)$input['edition_id']) {
                $distPlanDetID = (int)$pd->id;
            }
        }

        if ($distPlanDetID == 0) {
            $err = 'No editions were planned for this agent';
            throw new \Exception($err);
        }

        //Validate return amount
        $deliveryAmount = 0;
        $do = Delivery::with('distPlanDet')->where('dist_plan_det_id', '=', $distPlanDetID)->get();
        foreach($do as $x) {
            $deliveryAmount += $x->quota;
        }


        //Previous returns
        $returnAmount = 0;
        $prevRets = ReturnItem::where('dist_plan_det_id', '=', $distPlanDetID)->get();
        if ($prevRets) {
            foreach($prevRets as $x) {
                $returnAmount += $x->total;
            }
        }
        $total = $returnAmount + $input['total'];

        // Delivery amount is less than return
        if ($deliveryAmount < ($total)) {
            //Must fail
            $err = "Mismatched return amount!".
                   " Deliveries made = {$deliveryAmount}.".
                   " Total returned amount = {$total}.";
            throw new \Exception($err);

        }

        return $distPlanDetID;

    }

    /**
     * Return date should be 3 months due.
     *
     * Return date is based on `distributionPlan`.`publish_date`.
     * All distributionPlan should have 1-to-1 relationship
     * with edition. Therefore, if it not exist, return will fail.
     *
     * @Return null
     */
    private function validateReturnDate($input)
    {
        $distPlan = DistPlan::where('edition_id', '=', (int)$input['edition_id'])->firstOrFail();
        $distDate = new \DateTime($distPlan->publish_date);
        $distDate->add(new \DateInterval('P3M'));
        $retrDate = new \DateTime($input['date']);
        if ($retrDate > $distDate) {
            throw new \Exception('Item return has passed 3 months overdue!');
        }
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $returnItems = ReturnItem::with(
            'edition',
            'magazine',
            'invoiceDetail.invoice',
            'agent')->find($id);
        return view('circulation/return-view', ['returnItem'=>$returnItems]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
