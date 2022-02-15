<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class CodeTranslation extends Model{

   protected $table = 'code_translations';
   protected $fillable = ['code_word_id','language_id','translation'];


   
   /* 
	* Relationship
    */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function word()
    {
        return $this->belongsTo('App\Models\CodeWord');
    }

}