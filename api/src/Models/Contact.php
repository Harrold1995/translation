<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Contact extends Model{

   protected $table = 'contacts';
   protected $fillable = ['name','client_id','email','password','mobile','otp'];


   public function address()
    {
        return $this->belongsToMany('App\Models\Address','contact_addresses','contact_id','address_id');
    }

   /* 
	* Relationship
    */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }


}