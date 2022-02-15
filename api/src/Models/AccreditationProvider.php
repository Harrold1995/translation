<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class AccreditationProvider extends Model{

   protected $table = 'accreditation_providers';
   protected $fillable = ['provider_name','country'];


   /* 
	* Relationship
    */
    
   public function translators()
   {
       return $this->belongsToMany('App\Models\Translator','translator_accreditation_providers')->withPivot('accreditation_number');
   }

   public function subProvider()
    {
        return $this->hasMany('App\Models\SubAccreditationProvider');
    }


}