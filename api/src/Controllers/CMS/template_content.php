<?php

namespace App\Controllers\CMS;

use App\Models\CMS\TemplateContent;

class template_content extends BaseController {

	public static function get( int $templateId )
	{
		try
		{
			$s = TemplateContent::where('cms_template_id',$templateId)->orderBy('order')->get();
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "No content found for template $templateId." );
			}
		}
		catch( \Exception $e )
		{
		    throw new \Exception( $e->getMessage() ) ;
		}
	}
}