<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class DocumentLink extends Model{

   protected $table = 'document_language_link';
   protected $fillable = ['source_document_language','target_document_language'];

   /* 
	* Relationship
    */
    
    public function sourceDocumentLanguage()
    {
        return $this->belongsTo('App\Models\DocumentLanguage','source_document_language');
    }

    public function targetDocumentLanguage()
    {
        return $this->belongsTo('App\Models\DocumentLanguage','target_document_language');
    }

}