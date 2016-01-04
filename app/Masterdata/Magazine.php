<?php namespace App\Masterdata;

use Illuminate\Database\Eloquent\Model;

class Magazine extends Model {

    /**
     * Set editable columns
     */
    protected $fillable = ['name', 'publisher_id', 'period', 'price', 'percent_fee', 'percent_value'];

    /**
     * Define table connection to magazine
     */
    public function publisher()
    {
        return $this->belongsTo('App\Masterdata\Publisher');
    }

    /**
     * One-to-Many relationship of magazine->edition
     */
    public function edition()
    {
        return $this->hasMany('App\Masterdata\Edition');
    }

    /**
     * Define many-to-many relationship to `Agent`
     */
    public function agent()
    {
        return $this->belongsToMany('App\Masterdata\Agent');
    }
}
