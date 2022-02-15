<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Expertise extends Model{

   protected $table = 'expertise';
   protected $fillable = ['name','description'];

   protected $appends = ['check'];


   /* 
	* Relationship
    */
    
    public function languagePairs()
    {
        return $this->belongsToMany('App\Models\LanguagePair','language_pair_expertise');
    }

    /* 
	* Accessor
    */
    public function getCheckAttribute()
    {
            return false;
    }

}