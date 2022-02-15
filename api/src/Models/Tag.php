<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Tag extends Model{

   protected $table = 'tags';
   protected $fillable = ['tag','language_id'];

   public function terms()
   {
       return $this->belongsToMany('App\Models\Tag','term_tags');
   }

   public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function source()
    {
        return $this->hasMany('App\Models\TagsTranslation', 'source_tag_id');
    }

   public function target()
    {
        return $this->hasMany('App\Models\TagsTranslation', 'target_tag_id');
    }

    public function DocumentLanguage()
    {
        return $this->belongsToMany('App\Models\DocumentLanguage','document_tags','tag_id','document_language_id');
    }

}