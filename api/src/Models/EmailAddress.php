<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class EmailAddress extends Model{

   protected $table = 'email_addresses';
   protected $fillable = ['id','email'];

   /* 
	* Relationship
    */
    
    public function organisationContact()
   {
       return $this->belongsToMany('App\Models\OrganisationContacts','organisation_contact_email_link','email_id','organisation_contact_id');
   }

}