<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class getPostalCode extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'interpreters' );
	}


    public static function get( $intID )
    {
        $s = new self() ;
        $s->addField('postcode');
        $s->addWhere( 'fm_interpreter_id' , $intID ) ;
        $s->select() ;

        return $s->rows[0]->postcode;
    }

}



