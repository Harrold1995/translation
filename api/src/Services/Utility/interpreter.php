<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class interpreter extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'interpreters' );
	}

    public static function checkInterpreter( $intID )
    {
        $s = new self() ;
        $s->where( "fm_interpreter_id" , $intID) ;
        $s->addCount();
        $s->select() ;

        return ($s->rows[0]->num > 0) ? true : false;
    }


    public static function searchInterpreter($languageID, $interpreterName)
    {
        $s = new self() ;
        $s->distinct = true;
        $s->addJoin("interpreter_language_link", "fm_interpreter_id", "Interpreter ID");
        $s->where( "Language ID" , $languageID, '=' , 'interpreter_language_link') ;
        $s->in( 'Status Interpreting' , ['Preferred A' , 'Preferred B' , 'Trial' ] , 'interpreter_language_link') ;
        $s->addFieldCalc( 'interpreter_name' , "concat(interpreters.first_name, ' ', interpreters.surname)" );
        $s->addField("fm_interpreter_id", "interpreters", "int_id");
        $s->having('interpreter_name', " LIKE '$interpreterName' [calc]", '');
        $s->select() ;
        //echo $s->sql;

        return $s->rows;

        //select distinct b.fm_interpreter_id, concat(b.first_name, ' ', b.surname) AS fullname from interpreter_language_link as a, interpreters as b where
        //a.`Interpreter ID` = b.fm_interpreter_id and a.`Language ID` = 49 having fullname like '%SINGH%AUJLA%'
    }

}



