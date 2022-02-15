<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Organisation extends Model{

   protected $table = 'organisation';
   protected $fillable = ['name','state'];

   /* 
	* Relationship
    */
    
    public function documents()
    {
        return $this->belongsToMany('App\Models\Document', 'document_organisation');
    }
    
    public function contact()
    {
        return $this->hasMany('App\Models\OrganisationContacts');
    }

    public function address()
    {
        return $this->belongsToMany('App\Models\Address','organisation_address_link','organisation_id','address_id');
    }


}