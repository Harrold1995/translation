<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_content;


class Template extends Model
{
    protected $table = 'cms_templates';
    protected $fillable = ['cms_system_id','name','header','content','footer'];
    public $timestamps = false;
    protected $appends = ['templateContent'];


    /* 
	* Relationship
    */

    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }

    public function templateContent()
    {
        return $this->hasMany('App\Models\CMS\TemplateBlock','cms_template_id');
    }

    public function page()
    {
        return $this->hasMany('App\Models\CMS\Page','cms_template_id');
    }

    /* 
	* Accessor
    */
    public function getTemplateContentAttribute()
    {
        return template_content::get($this->id);
    }

    /* 
	* fn
    */

    public function html($id )
	{
		$html = '';
		foreach ( $this->templateContent as $item )
		{
			$html .= $item->html($id);
		}

		return $html;
	}

}
