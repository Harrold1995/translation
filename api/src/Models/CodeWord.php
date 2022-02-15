<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class CodeWord extends Model{

   protected $table = 'code_words';
   protected $fillable = ['name','term',];


   /* 
	* Relationship
    */
    public function codeTranslation()
    {
        return $this->hasMany('App\Models\CodeTranslation');
    }




}