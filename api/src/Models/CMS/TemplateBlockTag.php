<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_tags;


class TemplateBlockTag extends Model
{
    protected $table = 'cms_template_block_tags';
    protected $fillable = ['cms_template_block_id','cms_template_tag_id','order'];
    public $timestamps = false;
    protected $appends = ['templateTag'];

    /* 
	* Relationship
    */
    public function templateBlock()
    {
        return $this->belongsTo('App\Models\CMS\TemplateBlock','cms_template_block_id');
    }

    public function templateTag()
    {
        return $this->belongsTo('App\Models\CMS\TemplateTag','cms_template_tag_id');
    }

    /* 
	* Accessor
    */
    public function getTemplateTagAttribute()
    {
        return template_tags::instance( $this->cms_template_tag_id );
    }

    public function html($id)
	{
		return $this->templateTag->html($id);
	}

}
