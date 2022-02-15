<?php

namespace App\Controllers\CMS;

use App\Models\CMS\TemplateTagsContentLink;

class template_tags_content extends BaseController {

	public static function get( int $parentTemplateTagId )
	{
		try
		{
			$s = TemplateTagsContentLink::where('parent_cms_template_tag_id',$parentTemplateTagId)->orderBy('order')->get();
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "No template tag content found for template tag $parentTemplateTagId" );
			}
		}
		catch( \Exception $e )
		{
		    throw new \Exception( $e->getMessage() ) ;
		}
	}
}