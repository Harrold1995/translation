<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_tag_properties;
use App\Controllers\CMS\template_tags_content;
use App\Controllers\CMS\page_content;

use App\Services\html\tag;


class TemplateTag extends Model
{
    protected $table = 'cms_template_tags';
    protected $fillable = ['cms_system_id','name','tag','user_editable','page_content'];
    public $timestamps = false;
    protected $appends = ['templateTagProperty','content','contentPage'];

    /* 
	* Relationship
    */
    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }

    public function templateTagProperty()
    {
        return $this->hasMany('App\Models\CMS\TemplateTagProperty','cms_template_tag_id');
    }

    public function templateBlockTag()
    {
        return $this->hasMany('App\Models\CMS\TemplateBlockTag','cms_template_tag_id');
    }

    public function parentTag()
    {
        return $this->hasMany('App\Models\CMS\TemplateTagsContentLink', 'parent_cms_template_tag_id');
    }

    public function childTag()
    {
        return $this->hasMany('App\Models\CMS\TemplateTagsContentLink', 'child_cms_template_tag_id');
    }

    public function pageContentLink()
    {
        return $this->hasMany('App\Models\CMS\PageContentLink', 'cms_template_tag_id');
    }

    /* 
	* Accessor
    */
    public function getTemplateTagPropertyAttribute()
    {
        return template_tag_properties::get( $this->id );
    }

    public function getContentAttribute()
    {
        return template_tags_content::get( $this->id );      
    }


    function getContentPageAttribute ()
    {
        return page_content::get( $this->id );
    }

    public function html($id )
	{
		$tag = new tag( $this->tag );
		foreach ( $this->templateTagProperty as $property )
		{
			$property->add( $tag );
		}
		foreach ( $this->content as $item )
		{
			$tag->addContent( $item->html($id) );
		}
        foreach ( $this->contentPage as $item )
		{
			$tag->addContent( $item->html($id) );
		}

//		echo '<pre>' . print_r( $tag , true ) . '</pre>';

		return $tag->build();

	}

}
