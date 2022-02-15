<?php

namespace App\Controllers\CMS;

use App\Models\CMS\TemplateBlockTag;

class template_block_tags extends BaseController {

	public static function get( int $templateBlockId )
	{
		try
		{
			$s = TemplateBlockTag::where('cms_template_block_id',$templateBlockId)->orderBy('order')->get();
			if ( $s)
			{
				return $s;
			}
			else
			{
				throw new \Exception( "No tags found for Template block $templateBlockId." );
			}
		}
		catch( \Exception $e )
		{
		    throw new \Exception( $e->getMessage() ) ;
		}

	}

}