<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Url extends Model{

   protected $table = 'urls';
   protected $fillable = ['name','url'];

   /* 
	* Relationship
   */
    public function document()
    {
        return $this->belongsToMany('App\Models\Document');
    }

    public function urlCheck()
    {
        return $this->hasMany('App\Models\UrlCheck');
    }
    


}