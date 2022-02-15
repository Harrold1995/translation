<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 9/2/17
 * Time: 1:17 PM
 */

namespace API\traits;
use Slim\Http\Response;


trait returnError
{

	public function retErrorResponse( Response $response , $errMsg )
	{
		$obj = (object)['error' =>  $errMsg ];
		return $response->withJson( $obj ) ;

	}
}