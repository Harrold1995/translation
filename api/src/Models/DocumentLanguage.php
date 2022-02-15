<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentLanguage extends Model{

   protected $table = 'document_languages';
   protected $fillable = ['document_id','language_id','document_name'];

   /* 
	* Relationship
    */
    
    public function document()
    {
        return $this->belongsTo('App\Models\Document');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function source()
    {
        return $this->hasMany('App\Models\DocumentLink', 'target_document_language');
    }

    public function target()
    {
        return $this->hasMany('App\Models\DocumentLink', 'source_document_language');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag','document_tags','document_language_id','tag_id');
    }

    public function terms()
    {
        return $this->belongsToMany('App\Models\Term','document_terms','document_language_id','term_id');
    }

   

}