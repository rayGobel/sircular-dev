<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

class DistributionRealizationDetail extends Model 
{
    /**
     * 1-to-1 relation to DistributionPlan
     */
    public function distributionRealization()
    {
        return $this->belongsTo('App\Circulation\DistributionRealization','dist_real_id');
    }

    /**
     * 1-to-1 relation to agent
     */
    public function agent()
    {
        return $this->belongsTo('App\Masterdata\Agent');
    }

    /**
     * 1-to-many relation to delivery
     */
    public function delivery()
    {
        return $this->hasMany('App\Circulation\Delivery', 'dist_real_det_id');
    }

}
