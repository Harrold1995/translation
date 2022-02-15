<?php
namespace App\Controllers\CMS;

use App\Models\CMS\TemplateTagProperty;

class template_tag_properties extends BaseController {

	public static function get( int $templateTagId )
	{
		try
		{
			$s = TemplateTagProperty::where('cms_template_tag_id',$templateTagId)->get();
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "No Properties found." );
			}
		}
		catch( \Exception $e )
		{
		    throw new \Exception( $e->getMessage() ) ;
		}
	}
}