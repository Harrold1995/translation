<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Language extends Model{

   protected $table = 'languages';
   protected $fillable = ['language','ISO_639_1_code','ISO_639_3_code','word_press_code','right_to_left','native_name','include_in_tiles','include_in_dropdown'];

   protected $appends = ['checked_tiles','checked_dropdown'];



   /* 
	* Relationship
    */
    
   public function translators()
   {
       return $this->belongsToMany('App\Models\Translator','translator_languages');
   }

    public function translatorLanguage()
    {
        return $this->hasMany('App\Models\TranslatorLanguage');
    }
    
    public function terms()
    {
        return $this->hasMany('App\Models\Term');
    }
    
    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }

    public function documentsLanguage()
    {
        return $this->hasMany('App\Models\DocumentLanguage');
    }

    public function codeTranslation()
    {
        return $this->hasMany('App\Models\CodeTranslation');
    }


    /* 
	* Accessor
    */
    public function getCheckedTilesAttribute()
    {
            return $this->include_in_tiles === 0 ? false : true;
    }

    public function getCheckedDropdownAttribute()
    {
            return $this->include_in_dropdown === 0 ? false : true;
    }

}