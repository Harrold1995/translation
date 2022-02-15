<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Models\CMS\Template;
use App\Controllers\CMS\templates;


class Page extends Model
{
    protected $table = 'cms_pages';
    protected $fillable = ['cms_system_id','cms_template_id','name','page_content_block','default'];
    public $timestamps = false;
    protected $appends = ['template'];
 
    /* 
	* Relationship
    */

    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\CMS\Template','cms_template_id');
    }

    public function pageContent()
    {
        return $this->hasMany('App\Models\CMS\PageContentLink','cms_page_id');
    }


    /* 
	* Accessor
    */
    public function getTemplateAttribute()
    {
        return templates::instance($this->cms_template_id);
    }


    /* 
	* fn
    */

    public function html($id) : string
	{
        return $this->template->html($id);
	}
}
