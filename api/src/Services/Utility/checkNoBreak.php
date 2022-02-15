<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkNoBreak extends standardSqlRequest
{

	private $interpreter_id;

	function __construct()
	{
        parent::__construct( 'jobs' );
	}

    public static function get( $jobID )
    {
        $s = new self() ;
        //get data for the selected state
        $s->addFieldCalc( 'service_time_start' , "DATE_FORMAT(UTC_to_TS(  clients.state, `service_start` ), '%l:%i %p' )" );
        $s->addFieldCalc( 'service_time_end' , "DATE_FORMAT(UTC_to_TS( clients.state, `service_end` ), '%l:%i %p' )" );
        $s->where( " `job_session_id` IS NOT NULL [calc]" , '[isBoolean]' ) ;
        $s->where( "Ref No" , $jobID) ;


        $s->addJoin( 'clients' , 'Client ID');
        $s->addJoin( 'interpreters' , 'INT ID', 'fm_interpreter_id' );
        $s->select() ;
        //echo $s->sql;
        $s->clearWhere();  //clear where

        if ( $s->found ) {
            $timeDiff =  (strtotime($s->rows[0]->service_time_end) - strtotime($s->rows[0]->service_time_start) ) / 3600;
            $noBreak = ($timeDiff <= 5 ) ? 1 : 0;
            return $noBreak;
        } else
            return 0;


    }

}