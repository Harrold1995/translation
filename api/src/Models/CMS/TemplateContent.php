<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_blocks;


class TemplateContent extends Model
{
    protected $table = 'cms_template_content';
    protected $fillable = ['cms_template_id','cms_template_block_id','order'];
    public $timestamps = false;
    protected $appends = ['templateBlock'];

    /* 
	* Relationship
    */

    public function template()
    {
        return $this->belongsTo('App\Models\CMS\Template','cms_template_id');
    }

    public function templateBlock()
    {
        return $this->belongsTo('App\Models\CMS\TemplateBlock','cms_template_block_id');
    }

    /* 
	* Accessor
    */
    public function getTemplateBlockAttribute()
    {
        return template_blocks::instance($this->cms_template_block_id);
    }

    public function html($id )
	{
		return $this->templateBlock->html($id);
	}

}
