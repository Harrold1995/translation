<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class TermLink extends Model{

   protected $table = 'terms_link';
   protected $fillable = ['source_term_id','translated_term_id'];

   protected $appends = ['target_term','source_term'];

   /* 
	* Relationship
    */
    
    public function source()
    {
        return $this->belongsTo('App\Models\Term','source_term_id');
    }

    public function target()
    {
        return $this->belongsTo('App\Models\Term','translated_term_id');
    }

     /* 
	* Accessor
    */
    public function getTargetTermAttribute()
    {
            return $this->target->text;
    }

    public function getSourceTermAttribute()
    {
            return $this->source->text;
    }

}