<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Controllers\CMS\template_tags;
use App\Controllers\CMS\blocks;


class PageContentLink extends Model
{
    protected $table = 'cms_page_content_link';
    protected $fillable = ['cms_page_id','cms_template_tag_id','cms_block_id','order'];
    public $timestamps = false;
    protected $appends = ['block'];

    /* 
	* Relationship
    */
    public function page()
    {
        return $this->belongsTo('App\Models\CMS\Page','cms_page_id');
    }

    public function templateTag()
    {
        return $this->belongsTo('App\Models\CMS\TemplateTag','cms_template_tag_id');
    }

    public function templateBlock()
    {
        return $this->belongsTo('App\Models\CMS\Block','cms_block_id');
    }

    /* 
	* Accessor
    */

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

        if($id === $this->cms_page_id) {
            if ( isset( $this->block ) )
            {
                $html .= $this->block->html();
            }
        }
		
		return $html;
	}


}
