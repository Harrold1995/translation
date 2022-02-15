<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Term extends Model{

   protected $table = 'terms';
   protected $fillable = ['text','language_id'];

   protected $appends = ['language_name'];


   /* 
	* Relationship
    */
    
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
    
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag','term_tags');
    }

    public function translator()
   {
       return $this->belongsToMany('App\Models\Translator','term_translator');
   }  

   public function sourceTerms()
    {
        return $this->hasMany('App\Models\TermLink', 'source_term_id');
    }

    public function targetTerms()
    {
        return $this->hasMany('App\Models\TermLink', 'translated_term_id');
    }
     
    public function DocumentLanguage()
   {
       return $this->belongsToMany('App\Models\DocumentLanguage','document_terms','term_id','document_language_id');
   }  

    /* 
	* Accessor
    */
    public function getLanguageNameAttribute()
    {
            return $this->language->language;
    }

}