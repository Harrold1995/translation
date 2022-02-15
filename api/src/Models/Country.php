<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Country extends Model{

   protected $table = 'countries';
   protected $fillable = ['country'];

   /* 
	* Relationship
    */
    public function associations()
    {
        return $this->hasMany('App\Models\Association');
    }

    public function address()
    {
        return $this->hasMany('App\Models\Address');
    }

    /* 
	* Mutator
    */

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = ucwords($value);
    }


}