<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model 
{
    /**
     * Fillable columns
     */
    protected $fillable = ['dist_realization_det_id',
                           'date_issued',
                           'order_number',
                           'quota',
                           'consigned',
                           'gratis',
                           'number',
                           'in_invoice'
                          ];


    /**
     * 1-to-1 relation to distribution plan details
     */
    public function distRealizationDet()
    {
        return $this->belongsTo('App\Circulation\DistributionRealizationDetail',
                'dist_realization_det_id');
    }
}
