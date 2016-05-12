<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\StoreReturnItemRequest;
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
use App\Circulation\DistributionRealization
    as DistReal;
use App\Circulation\DistributionRealizationDetail
    as DistRealDet;

use App\Circulation\ReturnItem;
use App\Circulation\Delivery;
use App\Masterdata\Agent;
use App\Masterdata\Edition;
use Validator;

class ReturnController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $returnList = ReturnItem::with('distRealizationDet.agent',
                                   'distRealizationDet.distributionRealization.edition.magazine')
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
            [
                'agents'=>$agents,
                'editions'=>$editions,
                'edition_detail_action'=>action('ReturnController@postAddEditionDetail'),
                'form_action'=>action('ReturnController@store'),
                'return_item_count'=>1
            ]
        );

	}

	/**
	 * Store a newly created resource in storage.
	 *
     * Because I'm using separate request validator, all
     * actions done here will be limited to database input
     *
     * @param StoreReturnItemRequest $request
	 * @return Response
	 */
	public function store(StoreReturnItemRequest $request)
	{
        // Then, store necessary request
        $input = $request->only('agent_id', 'number', 'edition_id', 'total', 'date');
        $issueDate = strtotime($input['date']);
        $input['date'] = date('Y-m-d', $issueDate);
        //Validate if there are any Distribution plan
        $distRealDetID = Array();
        try {
            // Validate for each edition returned
            foreach($input['edition_id'] as $key=>$edition_id) {
                $distRealDetID[] = $this->validateReturnItem($input, $edition_id, $input['total'][$key]);
                $this->validateReturnDate($input, $edition_id);

            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        } catch (ModelNotFoundException $e) {
            // If we can't find data
            return redirect()->back()->withErrors('No deliveries were made!');
        }
        //Compose what to enter to database
        foreach($input['edition_id'] as $key=>$edition_id) {

            $ed = Edition::with('magazine')->find($edition_id);
            $toDB['dist_realization_det_id'] = $distRealDetID[$key];
            $toDB['agent_id'] = $input['agent_id'];
            $toDB['edition_id'] = $edition_id;
            $toDB['magazine_id'] = $ed->magazine->id;
            $toDB['date'] = $input['date'];
            $toDB['total'] = $input['total'][$key];
            $toDB['num'] = (int)$input['number'];
            $toDB['number'] = "{$edition_id}/".str_pad($toDB['num'], 6, 0, STR_PAD_LEFT);

            $newReturn = ReturnItem::firstOrCreate($toDB);
        }

        $msg = "Done! New Return # : {$newReturn->number}";
        return redirect("circulation/return")->with('message', $msg);
	}

    /**
     * return true if item are returnable
     *
     * Return criterias are:
     *  - return number is not existed before
     *  - sum is less than or equal to total delivered item
     *
     *  @param Array input from form validation
     *  @param int edition_id
     *  @param int total returned amount on form
     *
     *  @return int distRealDetID if passed all exceptions
     */
    private function validateReturnItem($input, $edition_id, $total)
    {
        // Check if return.num already existed
        $existed = ReturnItem::where('num', '=', (int)$input['number'])->get();
        if (!$existed->isEmpty()) {
            $err = "Return number is already existed! Return#: {$existed->implode('number')}";
            throw new \Exception($err);
        }

        // Check if agent_id match to distribution_plan
        $realizationDetail = DistRealDet::with('distributionRealization')->where('agent_id','=',$input['agent_id'])->get();
        if ($realizationDetail->isEmpty()) {
            $err = 'No delivery was made for this agent';
            throw new \Exception($err);
        }

        $distRealDetID = 0;
        // look for each distributionPlan
        foreach($realizationDetail as $rd) {
            if ($rd->distributionRealization->edition_id == (int)$edition_id) {
                $distRealDetID = (int)$rd->id;
            }
        }

        if ($distRealDetID == 0) {
            $err = 'No editions were delivered for this agent';
            throw new \Exception($err);
        }


        //Previous returns
        $returnAmount = 0;
        $prevRets = ReturnItem::where('dist_realization_det_id', '=', $distRealDetID)->get();
        if ($prevRets) {
            foreach($prevRets as $x) {
                $returnAmount += $x->total;
            }
        }
        $total = $returnAmount + $total;

        // Delivery amount is less than return
        $deliveryAmount = 0;
        $deliveries = Delivery::where('dist_realization_det_id', '=', $distRealDetID)->get();
        if ($deliveries) {
            foreach($deliveries as $x) {
                // Quota cannot be returned, so use consigned amount
                $deliveryAmount += $x->consigned;
            }
        }
        if ($deliveryAmount < ($total)) {
            //Must fail
            $err = "Mismatched return amount!".
                   " Deliveries made = {$deliveryAmount}.".
                   " Total returned amount = {$total}.";
            throw new \Exception($err);

        }

        return $distRealDetID;

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
    private function validateReturnDate($input, $edition_id)
    {
        $distReal = DistReal::where('edition_id', '=', $edition_id)->firstOrFail();
        $distDate = new \DateTime($distReal->publish_date);
        $distDate->add(new \DateInterval('P3M'));
        $retrDate = new \DateTime($input['date']);
        // Then, compare dates
        if ($retrDate > $distDate) {
            throw new \Exception('Item return has passed 3 months overdue!');
        }
    }

    /**
     * Add new input form. Only accept post
     *
     * @param  Request $request
     * @return Response
     */
    public function postAddEditionDetail(Request $request)
    {
        return redirect()
            ->back()
            ->withInput();
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
