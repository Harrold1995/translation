<?php

namespace App\Controllers\CMS;


use App\Models\CMS\Template;

class templates extends BaseController {

	public static function instance( int $id ) : Template
	{
		try
		{
			$s = Template::find($id);
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "Template $id not found" );
			}
		}
		catch( \Exception $e )
		{
			throw new \Exception( $e->getMessage() ) ;
		}


	}

}