<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;


class System extends Model
{
    protected $table = 'cms_systems';
    protected $fillable = ['name','url'];
    public $timestamps = false;

    /* 
	* Relationship
    */

    public function page()
    {
        return $this->hasMany('App\Models\CMS\Page','cms_system_id');
    }

    public function template()
    {
        return $this->hasMany('App\Models\CMS\Template','cms_system_id');
    }

    public function templateBlock()
    {
        return $this->hasMany('App\Models\CMS\TemplateBlock','cms_system_id');
    }

    public function templateTag()
    {
        return $this->hasMany('App\Models\CMS\TemplateTag','cms_system_id');
    }

    public function block()
    {
        return $this->hasMany('App\Models\CMS\Block','cms_system_id');
    }

    public function image()
    {
        return $this->hasMany('App\Models\CMS\Image','cms_system_id');
    }

    public function classProperty()
    {
        return $this->hasMany('App\Models\CMS\ClassProperty','cms_system_id');
    }

}
