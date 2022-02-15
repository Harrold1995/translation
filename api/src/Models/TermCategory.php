<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class TermCategory extends Model{

   protected $table = 'term_categories';
   protected $fillable = ['name'];


   /* 
	* Relationship
    */
    
    public function terms()
    {
        return $this->belongsToMany('App\Models\Term','term_category_link');
    }

    /* 
	* Accessor
    */

}