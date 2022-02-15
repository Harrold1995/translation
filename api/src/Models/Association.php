<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Association extends Model{

   protected $table = 'associations';
   protected $fillable = ['name','country'];

   public function translators()
   {
       return $this->belongsToMany('App\Models\Translator','translator_associations')->withPivot('member_number');
   }

   public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

}