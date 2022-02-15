<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkInterpreterReplacement extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'jobs' );
	}

    public static function check( $jobRef )
    {
        $s = new self() ;
        $s->addFieldCalc('relief', 'if (interpreters.`interpreter_id` = job_session_blocks.`interpreter_id`, 0, 1)');
        $s->addJoin("job_sessions", "job_session_id");
        $s->addJoin("job_session_blocks", "job_session_block_id", "job_session_block_id", "job_sessions");
        $s->addJoin("interpreters", "INT ID", "fm_interpreter_id");
        $s->where( "Ref No" , $jobRef, "=", "jobs") ;
        $s->select() ;
        return ($s->found) ? $s->rows[0]->relief : 0;
    }

}



