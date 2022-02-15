<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class LanguagePair extends Model{

   protected $table = 'language_pair';
   protected $fillable = ['translator_id','source_translator_language_id','target_translator_language_id'];

   protected $appends = ['pair', 'source','target','check'];


   /* 
	* Relationship
    */
    
    public function translator()
    {
        return $this->belongsTo('App\Models\Translator');
    }

    public function sourceLanguage()
    {
        return $this->belongsTo('App\Models\TranslatorLanguage', 'source_translator_language_id');
    }

    public function targetLanguage()
    {
        return $this->belongsTo('App\Models\TranslatorLanguage', 'target_translator_language_id');
    }

    public function expertise()
    {
        return $this->belongsToMany('App\Models\Expertise','language_pair_expertise');
    }
    
    public function subAccreditationProvider()
    {
        return $this->belongsToMany('App\Models\SubAccreditationProvider','language_pair_accreditation','language_pair_id','accreditation_provider_accreditations_id');
	}

    /* 
	* Accessor
    */
    public function getPairAttribute()
    {
            return $this->sourceLanguage->language_name . " to ". $this->targetLanguage->language_name;
    }
    public function getSourceAttribute()
    {
            return $this->sourceLanguage->language_name;
    }
    public function getTargetAttribute()
    {
            return $this->targetLanguage->language_name;
    }

    public function getCheckAttribute()
    {
            return false;
    }

}