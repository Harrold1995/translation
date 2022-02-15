<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class SubAccreditationProvider extends Model{

   protected $table = 'accreditation_provider_accreditations';
   protected $fillable = ['accreditation_provider_id','description','abbreviation','direction'];


   /* 
	* Relationship
    */
    
   public function languagePairs()
   {
       return $this->belongsToMany('App\Models\LanguagePair','language_pair_accreditation','accreditation_provider_accreditations_id','language_pair_id');
   }

   public function provider()
    {
        return $this->belongsTo('App\Models\AccreditationProvider');
    }

}