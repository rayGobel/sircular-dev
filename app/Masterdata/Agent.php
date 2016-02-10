<?php namespace App\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model 
{
    use SoftDeletes;

    /**
     * Attribute that should be mutated to dates
     */
    protected $dates = ['deleted_at'];

    /**
     * Define fillable columns on table
     */
    protected $fillable = ['name', 'city', 'agent_category_id',
                           'phone', 'contact'];

    /**
     * Define one-to-one relationship to `AgentCategories`
     */
    public function agent_category()
    {
        return $this->belongsTo('App\Masterdata\AgentCategory');
    }

    /**
     * Define many-to-many relationship to `Magazine`
     */
    public function magazine()
    {
        return $this->belongsToMany('App\Masterdata\Magazine');
    }



}
