<?php namespace App\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetailReturn extends Model 
{

    /**
     * Editable columns
     */
    protected $fillable = ['return_item_id',
                           'invoice_id',
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
        return $this->belongsTo('App\Invoice\Invoice');
    }

    /**
     * 1-to-1 reverse
     */
    public function returnItem()
    {
        return $this->belongsTo('App\Circulation\ReturnItem', 'return_item_id');
    }

    /**
     * 1-to-1 reverse
     */
    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }
}
