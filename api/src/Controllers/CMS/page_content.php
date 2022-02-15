<?php

namespace App\Controllers\CMS;

use App\Models\CMS\PageContentLink;

class page_content extends BaseController {

	public static function get( int $templateTagId )
	{
		try
		{
		    $s = PageContentLink::where('cms_template_tag_id',$templateTagId)->orderBy('order')->get();
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "No template tag content found for template tag $templateTagId" );
			}
		}
		catch( \Exception $e )
		{
		    throw new \Exception( $e->getMessage() ) ;
		}
	}
}