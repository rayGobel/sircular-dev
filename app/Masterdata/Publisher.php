<?php namespace App\Masterdata;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model {

    /**
     * Set editable columns
     */
    protected $fillable = ['name', 'city', 'province', 'phone', 'contact'];

    /**
     * Define table connection to magazine list
     */
    public function magazine()
    {
        return $this->hasOne('App\Masterdata\Magazine');
    }

}
