<?php
namespace App\Controllers\CMS;

use App\Models\CMS\Page;
use App\Controllers\CMS\page_model;
use App\Controllers\CMS\test_class;
use Illuminate\Database\Capsule\Manager as DB;

class cms_pages extends BaseController {

	public static function instance( int $id ) : Page
	{
		try
		{
			$s = Page::find($id);
			if ( $s )
			{
				return $s;
			}
			else
			{
				throw new \Exception( "Page $id not found" );
			}
		}
		catch( \Exception $e )
		{
		    throw new \Exception( $e->getMessage() ) ;
		}


	}
}