<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Address extends Model{

   protected $table = 'addresses';
   protected $fillable = ['title','address1','address_2','suburb','state','postcode','country'];

    /* 
	* Relationship
    */
    
    public function organisation()
   {
       return $this->belongsToMany('App\Models\Organisation','organisation_address_link','address_id','organisation_id');
   }

   public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function translator()
    {
        return $this->belongsToMany('App\Models\Translator','translator_addresses','address_id','translator_id');
    }

    public function contact()
    {
        return $this->belongsToMany('App\Models\Contact','contact_addresses','address_id','contact_id');
    }

    public function client()
    {
        return $this->belongsToMany('App\Models\Client','client_addresses','address_id','client_id');
    }
   

}