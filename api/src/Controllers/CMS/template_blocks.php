<?php

namespace App\Controllers\CMS;

use App\Models\CMS\TemplateBlock;

class template_blocks extends BaseController {

	public static function instance( int $id ) :TemplateBlock
	{
		try
		{
			$s = TemplateBlock::find($id);
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "Template Block $id not found" );
			}
		}
		catch( \Exception $e )
		{
			throw new \Exception( $e->getMessage() ) ;
		}


	}

}