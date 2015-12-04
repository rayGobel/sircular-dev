<?php namespace App\Masterdata;

use Illuminate\Database\Eloquent\Model;

class AgentCategory extends Model 
{

    /**
     * Fillable columns
     */
    protected $fillable = ['name', 'description'];

    /**
     * Define one-to-many relationship to `Agent`
     *
     * Each agent will have one category. It's possible
     * for each category to have multiple agents in it.
     * hence, one-to-many relationship.
     */
    public function agent()
    {
        return $this->hasMany('App\Masterdata\Agent');
    }

	//

}
