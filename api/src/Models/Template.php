<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Template extends Model{

   protected $table = 'templates';
   protected $fillable = ['type','name','template'];

   /* 
	* Relationship
    */
    
    // public function urlCheckEmail()
    // {
    //     return $this->belongsToMany('App\Models\UrlCheck','url_check_email','email_id','url_check_id');
    // }

}