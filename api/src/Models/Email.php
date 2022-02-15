<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Email extends Model{

   protected $table = 'emails';
   protected $fillable = ['to','subject','content','sent','error','errorMessage'];

   /* 
	* Relationship
    */
    
    public function urlCheckEmail()
    {
        return $this->belongsToMany('App\Models\UrlCheck','url_check_email','email_id','url_check_id');
    }

}