<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class num extends standardSqlRequest
{
	function __construct()
	{
        parent::__construct( 'job_nums' );
	}

    public static function getNum( $type = 'Web' ) {
        $j = new self( $type ) ;
        $j->setField( 'processing' , 1 ) ;
        $j->insert() ;
        return ( $j->error ) ? false : $j->last_insert_id ;
    }

}