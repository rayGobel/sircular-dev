<?php namespace App\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceConsign extends Model 
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
        return $this->hasMany('App\Invoice\InvoiceConsignDetailDelivery');
    }

    /**
     * 1-to-many relation to invoice detail return
     */
    public function detailReturn()
    {
        return $this->hasMany('App\Invoice\InvoiceConsignDetailReturn');
    }

    /**
     * 1-to-1 relation to magazine
     */
    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }

}
