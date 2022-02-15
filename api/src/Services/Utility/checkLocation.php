<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkLocation extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'client_locations' );
	}

    public static function validateLocation( $locationID, $clientID )
    {
        $s = new self() ;
        $s->where( "Child ID" , $locationID) ;
        $s->where( "Client ID" , $clientID) ;
        $s->select() ;

        if ( !$s->found ) {
            return false;
        } else {
            return true;
        }
    }


}



