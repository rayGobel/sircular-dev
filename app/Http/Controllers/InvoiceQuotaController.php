<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Invoice\InvoiceQuota;
use App\Invoice\InvoiceQuotaDetailDelivery
    as InvDelivery;
use App\Circulation\DistributionRealization
    as DistReal;
use App\Circulation\Delivery;
use App\Masterdata\Edition;
use App\Masterdata\Agent;

use App\Http\Requests\StoreInvoiceRequest;

class InvoiceQuotaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $invoices = InvoiceQuota::with('agent')->paginate(10);
        $invoices->setPath('');
        return view('invoice/invoice-quota-table', ['invoices'=>$invoices]);
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
        return view('invoice/invoice-form', ['invType'=>'Quota', 'agents'=>$agents, 'eds'=>$editions]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
     * @param StoreInvoiceRequest request
	 * @return Response
	 */
	public function store(StoreInvoiceRequest $request)
	{
        $input = $request->only('agent_id', 'edition_id', 'issue_date');
        // Validate requests before invoice was created. Such as:
        //   - Agent has not yet billed
        //   - Deliveries exist
        //
        //
        //get distribution plan executed for this agent and particular
        //  magazine for this date
        $distReal = DistReal::with('details.agent', 'edition.magazine')
            ->where('edition_id', '=', $input['edition_id'])->first();
        // only get distRealDet where agent_id matches
        foreach($distReal->details as $det) {
            if ((int)$det->agent_id == (int)$input['agent_id']) {
                $distRealDet = $det;
                break;
            }
        }

        //get necessary delivery for this month
        $deliveries = Delivery::with('distRealizationDet.distributionRealization.edition')
            ->whereRaw('dist_realization_det_id = ? AND in_invoice_quota = 0', [$distRealDet->id])
            ->get();
        //get returns for this month and 3 months ago with the same magazine
        $ed = Edition::with('magazine')->find((int)$input['edition_id']);

        //generate necessary data to go to invoice

        //generate data to create invoice
        $inv['num'] = $this->createInvNumber();
        $inv['number'] = "{$distReal->edition->magazine->id}/".str_pad($inv['num'], 5, 0, STR_PAD_LEFT);
        $inv['agent_id'] = $input['agent_id'];
        $issueDate = strtotime($input['issue_date']);
        $inv['issue_date'] = date('Y-m-d', $issueDate); //MySQL format
        $dueDate = strtotime('last friday of this month');
        $inv['due_date'] = date('Y-m-d', $dueDate);
        $inv['edition_id'] = (int)$input['edition_id'];
        // Invoice OK
        $newInv = InvoiceQuota::firstOrCreate($inv);

        // generate invoice delivery details
        foreach ($deliveries as $delv) {

            $toDB['delivery_id'] = $delv->id;
            $toDB['invoice_quota_id'] = $newInv->id;
            // Calculating price here
            // Current edition price, multiplied by quota amount
            $toDB['total'] = $delv->distRealizationDet->distributionRealization->edition->price * $delv->quota;
            $toDB['edition_id'] = $input['edition_id'];
            $newInvDelivery = InvDelivery::firstOrCreate($toDB);

            //Update in_invoice to true
            $delv->in_invoice_quota = 1;
            $delv->save();
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
        $latest = InvoiceQuota::orderBy('created_at')->first();
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
        $invDet = InvoiceQuota::with('edition.magazine',
            'agent',
            'detailDelivery.delivery.distRealizationDet.distributionRealization',
            'detailDelivery.edition'
        )->find($id);

        //Calculate Total invoice amount
        $totalDelivery = 0;
        $totalDeliveryDiscount = 0;
        //  count delivery
        foreach( $invDet->detailDelivery as $det ) {
            $totalDelivery += $det->total;
            $totalDeliveryDiscount += $det->discount;
        }

        return view('invoice/invoice-quota-details',
            ['invDet'=>$invDet,
            'invoiceID'=>$id,
            'totalDelivery'=>$totalDelivery,
            'totalDeliveryDiscount'=>$totalDeliveryDiscount
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
