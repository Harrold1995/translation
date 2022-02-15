<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;


class ClassProperty extends Model
{
    protected $table = 'cms_classes';
    protected $fillable = ['cms_system_id','class_name'];
    public $timestamps = false;

    /* 
	* Relationship
    */

    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }


}
