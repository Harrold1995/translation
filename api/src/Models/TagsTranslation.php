<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class TagsTranslation extends Model{

   protected $table = 'tag_translations';
   protected $fillable = ['source_tag_id','target_tag_id'];

   protected $appends = ['source_term'];

   /* 
	* Relationship
    */
    
  
    public function sourceTag()
    {
        return $this->belongsTo('App\Models\Tag', 'source_tag_id');
    }

    public function targetTag()
    {
        return $this->belongsTo('App\Models\Tag', 'target_tag_id');
    }

     /* 
	* Accessor
    */

    public function getSourceTermAttribute()
    {
            return $this->sourceTag->tag;
    }
}