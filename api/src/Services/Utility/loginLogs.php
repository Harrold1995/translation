<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class loginLogs extends standardSqlRequest
{

	private $interpreter_id;

	function __construct()
	{
        parent::__construct( 'logins' );
	}

    public static function checkIfLoggedIn( $interpreterID, $jobID )
    {
        $s = new self() ;

        $s->where( "fm_interpreter_id" , $interpreterID , '=', 'interpreters') ;
        $s->where( "job_id" , $jobID, '=', 'logins') ;
        $s->where( "type" , "Logon" ) ;
        $s->addJoin('interpreters', 'interpreter_id', 'interpreter_id');
        $s->select() ;
        $data = (count($s->rows) > 0 ) ? true : false;
        return $data;
    }

}



