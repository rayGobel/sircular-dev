<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

class DistributionPlanDetail extends Model 
{

    /**
     * Fillable columns
     */
    protected $fillable = ['distribution_plan_id',
                           'agent_id',
                           'quota',
                           'consigned',
                           'gratis'
                          ];

    /**
     * 1-to-1 relation to DistributionPlan
     */
    public function distributionPlan()
    {
        return $this->belongsTo('App\Circulation\DistributionPlan',
        'distribution_plan_id', 'id');
    }

    /**
     * 1-to-1 relation to agent
     */
    public function agent()
    {
        return $this->belongsTo('App\Masterdata\Agent');
    }

}
