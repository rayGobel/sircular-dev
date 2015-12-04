<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model 
{
    /**
     * Fillable columns
     */
    protected $fillable = ['dist_plan_det_id',
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
    public function distPlanDet()
    {
        return $this->belongsTo('App\Circulation\DistributionPlanDetail',
                                'dist_plan_det_id');
    }
}
