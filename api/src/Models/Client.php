<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Client extends Model{

   protected $table = 'clients';
   protected $fillable = ['name','email','country'];

   /* 
	* Relationship
    */
    public function contact()
    {
        return $this->hasMany('App\Models\Contact');
    }
    
    public function documents()
    {
        return $this->belongsToMany('App\Models\Document', 'client_documents');
	}

    public function address()
    {
        return $this->belongsToMany('App\Models\Address','client_addresses','client_id','address_id');
    }


}