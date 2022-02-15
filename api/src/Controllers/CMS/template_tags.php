<?php

namespace App\Controllers\CMS;

use App\Models\CMS\TemplateTag;

class template_tags extends BaseController {

	public static function instance( ?int $id ) : TemplateTag
	{
		try
		{
			$s = TemplateTag::find($id);
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "Tag $id not found" );
			}
		} catch ( \Exception $e )
		{
			throw new \Exception( $e->getMessage() );
		}
	}

}