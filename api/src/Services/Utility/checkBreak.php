<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkBreak extends standardSqlRequest
{

	private $interpreter_id;

	function __construct()
	{
        parent::__construct( 'activities' );
	}

    public static function checkInterpreterBreak( $jobID )
    {
        $s = new self() ;
        $s->where( "job_id" , $jobID) ;
        $s->where( "activity_type_id" , 7 ) ;
        $s->addField('actual_time_start');
        $s->addField('actual_time_end');
        $s->addFieldCalc('current_time', 'UTC_to_TS(clients.state, UTC_TIMESTAMP())');
        $s->where( 'deleted IS NULL [calc]', '[isBoolean]' ) ;
        $s->addJoin( 'jobs' , 'job_id', 'Ref No');
        $s->addJoin( 'clients' , 'Client ID', 'Client ID', 'jobs');
        $s->select() ;

        if ( !$s->found ) {
            return 0;
        } else {
            if ($s->rows[0]->actual_time_end != '') {
                return 3;
            } else if ($s->rows[0]->actual_time_start == '') {
                return 0;
            } else {
                //35 minutes interval for lunch break once its over the interval
                $interval = 35 * 60; //(35 minutes * 60 seconds)
                $timeStart = strtotime($s->rows[0]->actual_time_start); //get time in seconds (state format)
                //$currentTime = mktime(date('H'), date('i'), date('s')); //get time in seconds (UTC format)
                $currentTime = strtotime($s->rows[0]->current_time); //get time in seconds (state format)
                $timeDiff = ($currentTime - $timeStart);
                if ($timeDiff > $interval) {
                    return 2;
                } else {
                    return 1;
                }
            }

        }
    }

    public static function getBreakConsumption( $jobID )
    {
        $s = new self() ;
        $s->where( "job_id" , $jobID) ;
        $s->where( "activity_type_id" , 7 ) ;
        $s->addField('actual_time_start');
        $s->addField('actual_time_end');
        $s->addFieldCalc('current_time', 'UTC_to_TS(clients.state, UTC_TIMESTAMP())');
        $s->where( 'deleted IS NULL [calc]', '[isBoolean]' ) ;
        $s->addJoin( 'jobs' , 'job_id', 'Ref No');
        $s->addJoin( 'clients' , 'Client ID', 'Client ID', 'jobs');
        $s->select() ;


        //35 minutes interval for lunch break once its over the interval
        $interval = 35 * 60; //(35 minutes * 60 seconds)
        $timeStart = strtotime($s->rows[0]->actual_time_start); //get time in seconds (state format)
        //$currentTime = mktime(date('H'), date('i'), date('s')); //get time in seconds (UTC format)
        $currentTime = strtotime($s->rows[0]->current_time); //get time in seconds (state format)
        $timeDiff = ($currentTime - $timeStart);
        return abs(ceil($timeDiff / 60));
    }

}



