<?php namespace App\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceQuotaDetailDelivery extends Model 
{

    /**
     * Editable columns
     */
    protected $fillable = ['delivery_id',
                           'invoice_quota_id',
                           'vat',
                           'discount',
                           'total',
                           'edition_id'
                          ];

    /**
     * 1-to-1 reverse
     */
    public function invoice()
    {
        return $this->belongsTo('App\Invoice\InvoiceQuota');
    }

    /**
     * 1-to-1 reverse
     */
    public function delivery()
    {
        return $this->belongsTo('App\Circulation\Delivery');
    }

    /**
     * 1-to-1 reverse
     */
    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }

}
