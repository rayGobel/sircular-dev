<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Invoice\Invoice;
use App\Invoice\InvoiceDetailDelivery
    as InvDelivery;
use App\Invoice\InvoiceDetailReturn
    as InvReturn;
use App\Circulation\DistributionPlan
    as DistPlan;
use App\Circulation\Delivery;
use App\Circulation\ReturnItem;
use App\Masterdata\Edition;
use App\Masterdata\Agent;

class InvoiceController extends Controller {

    protected $rules = [
        'agent_id'=>'required|numeric',
        'edition_id'=>'required|numeric',
        'issue_date'=>'required|date_format:d-m-Y'
        ];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $invoices = Invoice::with('agent')->paginate(10);
        $invoices->setPath('');
        return view('invoice/invoice-table', ['invoices'=>$invoices]);

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
        return view('invoice/invoice-form', ['agents'=>$agents, 'eds'=>$editions]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, $this->rules);
        $input = $request->only('agent_id', 'edition_id', 'issue_date');
        //get distribution plan executed for this agent and particular
        //  magazine for this date
        $distPlan = DistPlan::with('details.agent', 'edition.magazine')
            ->where('edition_id', '=', $input['edition_id'])->first();
        foreach($distPlan->details as $det) {
            if ((int)$det->agent_id == (int)$input['agent_id']) {
                $distPlanDet = $det;
                break;
            }
        }

        //get necessary delivery for this month
        $deliveries = Delivery::with('distPlanDet.distributionPlan.edition')
            ->whereRaw('dist_plan_det_id = ? AND in_invoice = 0', [$distPlanDet->id])->get();
        //get returns for this month and 3 months ago with the same magazine
        $ed = Edition::with('magazine')->find((int)$input['edition_id']);
        // just use scopeInvoice (see: http://laravel.com/docs/5.0/eloquent#query-scopes)
        $returns = ReturnItem::with('distPlanDet.distributionPlan.edition')
            ->Invoice( (int)$input['agent_id'], (int)$ed->magazine->id )->get();

        //generate necessary data to go to invoice

        //generate data to create invoice
        $inv['num'] = $this->createInvNumber();
        $inv['number'] = "{$distPlan->edition->magazine->id}/".str_pad($inv['num'], 5, 0, STR_PAD_LEFT);
        $inv['agent_id'] = $input['agent_id'];
        $issueDate = strtotime($input['issue_date']);
        $inv['issue_date'] = date('Y-m-d', $issueDate); //MySQL format
        $dueDate = strtotime('last friday of this month');
        $inv['due_date'] = date('Y-m-d', $dueDate);
        $inv['edition_id'] = (int)$input['edition_id'];
        // Invoice OK
        $newInv = Invoice::firstOrCreate($inv);

        // generate invoice delivery details
        foreach ($deliveries as $delv) {

            $toDB['delivery_id'] = $delv->id;
            $toDB['invoice_id'] = $newInv->id;
            // Calculating price here
            // Current delivery price, multiplied by quota
            $toDB['total'] = $delv->distPlanDet->distributionPlan->edition->price * $delv->quota;
            $toDB['edition_id'] = $input['edition_id'];
            $newInvDelivery = InvDelivery::firstOrCreate($toDB);

            //Update in_invoice to true
            $delv->in_invoice = 1;
            $delv->save();
        }
        $thisMonthPrice = $delv->distPlanDet->distributionPlan->edition->price;
        unset($toDB);

        foreach ($returns as $ret) {

            $toDB['return_item_id'] = $ret->id;
            $toDB['invoice_id'] = $newInv->id;
            // Calculating price here. We will calculate discount based on:
            //   thisMonthPrice * total return - returnMonthPrice * total return
            $returnMonthPrice = $ret->distPlanDet->distributionPlan->edition->price;
            $toDB['discount'] = $ret->total * ($thisMonthPrice-$returnMonthPrice);
            // of course, total will  use thisMonthPrice, even though returned
            // item may have different price.
            $toDB['total'] = $thisMonthPrice * $ret->total;
            $toDB['edition_id'] = $input['edition_id'];
            $newInvDelivery = InvReturn::firstOrCreate($toDB);

            //Update in_invoice
            $ret->in_invoice = 1;
            $ret->save();
        }

        $msg = "Done! New Invoice# : {$newInv->number}";
        return redirect("invoice/invoice")->with('message', $msg);

	}

    /**
     * createInvNumber return new invoice# after checking for latest num in database
     *
     * @return int num
     */
    private function createInvNumber()
    {
        $latest = Invoice::orderBy('created_at')->first();
        if (empty($latest)) {
            $num = 1;
        } else {
            $num = $latest->num + 1;
        }
        return $num;
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $invDet = Invoice::with('edition.magazine',
            'agent',
            'detailDelivery.delivery.distPlanDet.distributionPlan',
            'detailDelivery.edition',
            'detailReturn.edition',
            'detailReturn.returnItem')->find($id);

        //Calculate Total invoice amount
        $totalDelivery = 0;
        $totalDeliveryDiscount = 0;
        $totalReturn = 0;
        $totalReturnDiscount = 0;
        //  count delivery
        foreach( $invDet->detailDelivery as $det ) {
            $totalDelivery += $det->total;
            $totalDeliveryDiscount += $det->discount;
        }
        //  count return
        foreach( $invDet->detailReturn as $det ) {
            $totalReturn += $det->total;
            $totalReturnDiscount += $det->discount;
        }

        return view('invoice/invoice-details',
            ['invDet'=>$invDet,
            'invoiceID'=>$id,
            'totalDelivery'=>$totalDelivery,
            'totalDeliveryDiscount'=>$totalDeliveryDiscount,
            'totalReturn'=>$totalReturn,
            'totalReturnDiscount'=>$totalReturnDiscount
            ] );
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
