<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;


class Image extends Model
{
    protected $table = 'cms_images';
    protected $fillable = ['cms_system_id','name','src'];
    public $timestamps = false;

    /* 
	* Relationship
    */

    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }

}
