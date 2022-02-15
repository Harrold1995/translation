<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_tags;
use App\Controllers\CMS\blocks;


class TemplateTagsContentLink extends Model
{
    protected $table = 'cms_template_tags_content_link';
    protected $fillable = ['parent_cms_template_tag_id','child_cms_template_tag_id','cms_block_id','order'];
    public $timestamps = false;
    protected $appends = ['tag','block'];

    /* 
	* Relationship
    */
    public function parentTag()
    {
        return $this->belongsTo('App\Models\CMS\TemplateTag','parent_cms_template_tag_id');
    }

    public function childTag()
    {
        return $this->belongsTo('App\Models\CMS\TemplateTag','child_cms_template_tag_id');
    }

    public function block()
    {
        return $this->belongsTo('App\Models\CMS\Block','cms_block_id');
    }

    /* 
	* Accessor
    */
    public function getTagAttribute()
    {
        try
		{
            return template_tags::instance( $this->child_cms_template_tag_id );
		}
		catch( \Exception $e )
		{
			// tag and block can be null. need to test for this before calling object methods
		}
       ;
    }

    public function getBlockAttribute()
    {
        try
		{
            return blocks::instance( $this->cms_block_id );
		}
		catch( \Exception $e )
		{
			// tag and block can be null. need to test for this before calling object methods
		}
       
    }

    public function html( $id ) : string
	{
		$html = '';
		if (isset( $this->tag ))
		{
			$html .= $this->tag->html($id);
		}
		if ( isset( $this->block ) )
		{
			$html .= $this->block->html();
		}
		return $html;
	}


}
