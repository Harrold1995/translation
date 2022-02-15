<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class getLanguage extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'interpreter_language_link' );
	}


    public static function get( $intID )
    {
        $s = new self() ;
        $s->addWhere( 'Interpreter ID' , $intID ) ;
        $s->addJoin( 'languages' , 'Language ID' ) ;
        $s->rowKeyField = 'Language' ;
        $s->select() ;

        foreach ( $s->rows as $key => $lang )
            if ( $lang->{'web access'} ) @ $langs[] = $lang->Language ;

        return "('" . join( "','" , @$langs ) . "')" ;
    }

}



