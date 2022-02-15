<?php

namespace App\Controllers\CMS;

use App\Models\CMS\Block;

class blocks extends BaseController {

	public static function instance( ?int $id ) : Block
	{
		try
		{
			if ( $id == null )
			{
				throw new \Exception( "Id is null no search performed" );
			}
			$s = Block::find($id);
			if ( $s )
			{
				return $s;
			}
			else
			{
				// echo '<pre>' . print_r( $s , true ) . '</pre>'; exit;
				throw new \Exception( "Block $id not found" );
			}
		}
		catch( \Exception $e )
		{
			throw new \Exception( $e->getMessage() ) ;
		}


	}

}