<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 9/8/17
 * Time: 3:41 PM
 */

namespace API\traits;

use Slim\Http\Response ;

trait returnJson
{
	public function returnJson( Response $response , $obj )
	{
		$response = $response->withJson( $obj ) ;
		return $response ;
	}
}