<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;
use App\Circulation\DistributionPlan 
    as DistPlan;
use App\Circulation\DistributionPlanDetail 
    as DistPlanDet;

use App\Circulation\Delivery;

class DeliveryController extends Controller {

    /**
     * new DO# rules
     */
    protected $rules = [
        'date_issued'=>'required|date_format:d-m-Y',
        'quota'=>'numeric',
        'consigned'=>'numeric',
        'gratis'=>'numeric'
        ];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $deliveryLists = Delivery::with('distPlanDet.distributionPlan.edition.magazine',
            'distPlanDet.agent')->paginate(10);

        $deliveryLists->setPath('');
        return view('circulation/delivery-list',
            ['deliveryLists'=>$deliveryLists,
             'createButton'=>false]);
	}

    /**
     * return specific deliveries based on distPlanID and its detail
     *
     * @param int distPlanID refer to distribution plan pkey
     * @param int distPLanDetID refer to distribution plan details pkey
     * @return response
     */
    public function ScopeIndex($distPlanID, $distPlanDetID)
    {
        $deliveryLists = Delivery::with('distPlanDet.distributionPlan.edition.magazine',
            'distPlanDet.agent')->where('dist_plan_det_id', '=', $distPlanDetID)->paginate(10);

        $deliveryLists->setPath('');
        return view('circulation/delivery-list',
            ['deliveryLists'=>$deliveryLists,
             'createButton'=>true]);

    }

	/**
	 * Show the form for creating a new delivery order
     *
     * Necessary arguments are already created in `distPlanDet` model.
     * This form will fill out remaining DO contents such as:
     *   - issue date
     *   - DO#
     * 
     * This DO# should be immutable once created to preserve
     * consistency between delivery, return item and invoices.
	 *
     * @param int distPlanID refer to distribution plan details pkey
	 * @return Response
	 */
	public function create($distPlanID, $detailsID)
	{
		//Get necessary details to fill out form
        $detail = DistPlanDet::with('agent', 'distributionPlan.edition.magazine')
            ->find($detailsID);

        return view('circulation/delivery-form',
            ['distPlanID' => $distPlanID,
             'detailsID'=>$detailsID,
             'detail'=>$detail
            ]
        );

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($distPlanID, $detailsID, Request $request)
	{
        //Validate
        $this->validate($request, $this->rules);
        $input = $request->only('date_issued', 'quota', 'consigned', 'gratis');
        //Convert to MySQL date first
        $issueDate = strtotime($input['date_issued']);
        $input['date_issued'] = date('Y-m-d', $issueDate);
        $year = date('y');
        $num = Delivery::max('number')+1;
        $input['number'] = $num;
        $input['order_number'] = "{$year}/".str_pad($num, 5, 0, STR_PAD_LEFT);
        $input['dist_plan_det_id'] = $detailsID;

        $newDO = Delivery::firstOrCreate($input);
        $msg = "Done! New DO# : {$newDO->order_number}";
        return redirect("circulation/distribution-plan/$distPlanID")->with('message', $msg);
            
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($distPlanID, $detailsID, $id)
	{
        $delivery = DistPlanDet::with('distributionPlan.edition.magazine', 'agent', 'delivery')->find($detailsID);
        return view('circulation/delivery-details',
            ['dlv'=>$delivery, 'deliveryID'=>$id]);
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
        try {
            $delivery = Delivery::findOrFail($id);

            $delivery->delete();
            $execMsg = "Delete Successful!";
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('circulation/delivery')->with('errMsg', $execMsg);
        }
        return redirect('circulation/delivery')->with('message', $execMsg);
	}

}
