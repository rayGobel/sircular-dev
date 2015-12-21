<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

class DistributionRealization extends Model 
{
    /**
     * 1-to-1 relation to distributionPlan
     */
    public function distPlan()
    {
        return $this->belongsTo('App\Circulation\DistributionPlan');
    }

    /**
     * 1-to-1 relation to Edition (for quicker relationship access)
     */
    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }

}
