<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 9/2/17
 * Time: 1:03 PM
 */

namespace API\traits;

use API\functions\debug;
use API\sql\where;
use Slim\Http\Response;


trait addToDb
{
	function save( Response $response )
	{
		$this->insert();
		$obj = (object)['error' => ( $this->error ) ? $this->error : false];

		if (  $this->affected && $this->last_insert_id )
		{
			$this->where( $this->primaryKeyField , $this->last_insert_id ) ;
			$this->select( ) ;
			$obj->data = $this->rows[0] ;
		}
		else
		{
			$obj->error = 'Data unable to be inserted' ;
		}

		return $response->withJson( $obj ) ;

	}
}