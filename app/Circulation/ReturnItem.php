<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

/**
 * word `return` is reserved php words.
 * I should change to ReturnItem
 */
class ReturnItem extends Model 
{

    /**
     * Editable columns
     */
    protected $fillable = ['dist_realization_det_id',
                           'agent_id',
                           'edition_id',
                           'magazine_id',
                           'date',
                           'number',
                           'num',
                           'total',
                           'in_invoice'
                          ];

    public function distRealizationDet()
    {
        return $this->belongsTo('App\Circulation\DistributionRealizationDetail',
                                'dist_realization_det_id');

    }

    public function agent()
    {
        return $this->belongsTo('App\Masterdata\Agent');
    }

    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }

    public function magazine()
    {
        return $this->belongsTo('App\Masterdata\Magazine');
    }

    public function scopeInvoice($query, $agent_id, $magazine_id, $inpDate=NULL)
    {
        if($inpDate) {
            $dueDate = strtotime($inpDate);
        }
        else{ 
            $dueDate = strtotime("first day of this month -3 months"); 
        }
        $last3month = date('Y-m-d', $dueDate);
        return $query->where('magazine_id', '=', $magazine_id)
                     ->where('in_invoice', '=', 0)
                     ->where('date', '>=', $last3month)
                     ->where('agent_id', '=', $agent_id);

    }

    public function invoiceDetail()
    {
        return $this->hasOne('App\Invoice\InvoiceDetailReturn');
    }

}
