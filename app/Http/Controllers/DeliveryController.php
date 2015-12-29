<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;
use App\Circulation\DistributionRealization
    as DistRealization;
use App\Circulation\DistributionRealizationDetail
    as DistRealizationDet;

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
        $deliveryLists = Delivery::with('distRealizationDet.distributionRealization.edition.magazine',
            'distRealizationDet.agent')->paginate(10);

        $deliveryLists->setPath('');
        return view('circulation/delivery-list',
            ['deliveryLists'=>$deliveryLists,
             'createButton'=>false]);
	}

    /**
     * return specific deliveries based on distRealizationID and its detail
     *
     * @param int distPlanID refer to distribution plan pkey
     * @param int distPLanDetID refer to distribution plan details pkey
     * @return response
     */
    public function ScopeIndex($distRealizationID, $distRealizationDetID)
    {
        $deliveryLists = Delivery::with('distRealizationDet.distributionRealization.edition.magazine',
            'distRealizationDet.agent')->where('dist_realization_det_id', '=', $distRealizationDetID)->paginate(10);

        $deliveryLists->setPath('');
        return view('circulation/delivery-list',
            ['deliveryLists'=>$deliveryLists,
             'createButton'=>true]);

    }

	/**
	 * Show the form for creating a new delivery order
     *
     * Necessary arguments are already created in `distRealizationDet` model.
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
	public function create($distRealizationID, $distRealizationDetID)
	{
		//Get necessary details to fill out form
        $detail = DistRealizationDet::with('agent', 'distributionRealization.edition.magazine')
            ->find($distRealizationDetID);

        return view('circulation/delivery-form',
            ['distRealizationID' => $distRealizationID,
             'distRealizationDetID'=>$distRealizationDetID,
             'detail'=>$detail
            ]
        );

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($distRealizationID, $distRealizationDetID, Request $request)
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
        $input['dist_realization_det_id'] = $distRealizationDetID;

        $newDO = Delivery::firstOrCreate($input);
        // Modify delivery.realization.details
        $distRealizationDet = DistRealizationDet::find($distRealizationDetID);
        $distRealizationDet->quota = $distRealizationDet->quota + $input['quota'];
        $distRealizationDet->consigned = $distRealizationDet->consigned + $input['consigned'];
        $distRealizationDet->gratis = $distRealizationDet->gratis + $input['gratis'];
        $distRealizationDet->save();
        $msg = "Done! New DO# : {$newDO->order_number}";
        return redirect("circulation/distribution-realization/{$distRealizationID}")->with('message', $msg);
            
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($distRealizationID, $distRealizationDetID, $id)
	{
        $delivery = DistRealizationDet::with('distributionRealization.edition.magazine', 'agent', 'delivery')
            ->find($distRealizationDetID);

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
