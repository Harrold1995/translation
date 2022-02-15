<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_block_tags;


class TemplateBlock extends Model
{
    protected $table = 'cms_template_blocks';
    protected $fillable = ['cms_system_id','name','type'];
    public $timestamps = false;
    protected $appends = ['templateBlockTag'];

    /* 
	* Relationship
    */

    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }

    public function templateContent()
    {
        return $this->hasMany('App\Models\CMS\TemplateBlock','cms_template_block_id');
    }

    public function templateBlockTag()
    {
        return $this->hasMany('App\Models\CMS\TemplateBlockTag','cms_template_block_id');
    }

    /* 
	* Accessor
    */
    public function getTemplateBlockTagAttribute()
    {
        return template_block_tags::get($this->id);
    }

    public function html($id) : string
	{
		$html = '';
		foreach ( $this->templateBlockTag as $tag )
		{
			$html .= $tag->html($id);
		}

		return $html;
	}


}
