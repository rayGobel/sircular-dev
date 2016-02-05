<?php namespace App\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceQuota extends Model 
{

    /**
     * Editable columns
     */
    protected $fillable = ['num',
                           'number',
                           'edition_id',
                           'adjustment',
                           'agent_id',
                           'issue_date',
                           'due_date'
                          ];

    /**
     * 1-to-1 relation to agent
     */
    public function agent()
    {
        return $this->belongsTo('App\Masterdata\Agent');
    }

    /**
     * 1-to-many relation to invoice detail delivery
     */
    public function detailDelivery()
    {
        return $this->hasMany('App\Invoice\InvoiceQuotaDetailDelivery');
    }

    /**
     * 1-to-1 relation to magazine
     */
    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }
}
