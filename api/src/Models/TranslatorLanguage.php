<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;


class TranslatorLanguage extends Model{

   protected $table = 'translator_languages';
   protected $fillable = ['translator_id','language_id'];

   protected $appends = ['language_name'];

   /* 
	* Relationship
    */
    
    public function translator()
    {
        return $this->belongsTo('App\Models\Translator');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function source()
    {
        return $this->hasMany('App\Models\LanguagePair', 'source_translator_language_id');
    }

   public function target()
    {
        return $this->hasMany('App\Models\LanguagePair', 'target_translator_language_id');
    }
   
    /* 
	* Accessor
    */
    public function getLanguageNameAttribute()
    {
            return $this->language->language;
    }

}