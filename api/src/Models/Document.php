<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Document extends Model{

   protected $table = 'documents';
   protected $fillable = ['type','type_id'];

   protected $appends = ['url_data', 'organisation','client_name','translators'];

   /* 
	* Relationship
    */
    
    public function translator()
    {
        return $this->belongsToMany('App\Models\Translator','document_translators');
    }

    public function client()
    {
        return $this->belongsToMany('App\Models\Client','client_documents');
    }

    public function organization()
    {
        return $this->belongsToMany('App\Models\Organisation','document_organisation');
    }

    public function link()
    {
        return $this->belongsToMany('App\Models\Url');
    }

    public function documentsLanguage()
    {
        return $this->hasMany('App\Models\DocumentLanguage');
    }

    /* 
	* Accessor
    */
    public function getUrlDataAttribute()
    {
            return $this->link[0]->url;
    }
    public function getOrganisationAttribute()
    {
            return $this->organization[0]->name;
    }  
    public function getClientNameAttribute()
    {
            return $this->client[0]->name;
    }  

    public function getTranslatorsAttribute()
    {
            return $this->translator;
    } 
}