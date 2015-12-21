<?php namespace App\Circulation;

use Illuminate\Database\Eloquent\Model;

class DistributionPlan extends Model 
{
    /**
     * Editable columns
     */
    protected $fillable = ['edition_id',
                           'percent_fee',
                           'value_fee',
                           'print',
                           'gratis',
                           'distributed',
                           'stock',
                           'publish_date',
                           'print_number',
                           'is_realized'
                          ];

    /**
     * 1-to-1 relation to Edition
     */
    public function edition()
    {
        return $this->belongsTo('App\Masterdata\Edition');
    }

    /**
     * 1-to-many relation to DistributionPlanDetail
     */
    public function details()
    {
        return $this->hasMany('App\Circulation\DistributionPlanDetail','distribution_plan_id', 'id');
    }
	

}
