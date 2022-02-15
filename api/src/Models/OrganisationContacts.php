<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class OrganisationContacts extends Model{

   protected $table = 'organisation_contacts';
   protected $fillable = ['organisation_id','name','phone','mobile'];

   /* 
	* Relationship
    */
    
    public function organisation()
    {
        return $this->belongsTo('App\Models\Organisation');
    }

    public function email()
    {
        return $this->belongsToMany('App\Models\EmailAddress','organisation_contact_email_link','organisation_contact_id','email_id');
    }

}