<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkLanguage extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'languages' );
	}

    public static function validateLanguage( $language )
    {
        $s = new self() ;
        $s->where( "Language" , $language) ;
        $s->select() ;

        if ( !$s->found ) {
            return false;
        } else {
            return true;
        }
    }

    public static function validateLanguageID( $languageID )
    {
        $s = new self() ;
        $s->where( "Language ID" , $languageID) ;
        $s->select() ;

        if ( !$s->found ) {
            return false;
        } else {
            return true;
        }
    }


}



