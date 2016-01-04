<?php namespace App\Masterdata;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model 
{
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
