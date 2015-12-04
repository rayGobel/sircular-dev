<?php namespace App\Masterdata;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model 
{
    /**
     * Editable columns
     */
    protected $fillable = ['magazine_id',
                           'edition_code',
                           'main_article',
                           'cover',
                           'price'];

    /**
     * Table connections
     */
    public function magazine()
    {
        return $this->belongsTo('App\Masterdata\Magazine');
    }

    /**
     * Filter scope
     */
    public function scopeFilter($query, $magazine_id)
    {
        return $query->where('magazine_id', '=', $magazine_id);
    }

}
