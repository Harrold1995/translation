<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkClockOff extends standardSqlRequest
{

	private $interpreter_id;

	function __construct()
	{
        parent::__construct( 'activities' );
	}

    public static function checkEarlyClockOff( $jobID, $jobEndTime)
    {
        $s = new self() ;
        $s->where( "job_id" , $jobID) ;
        $s->where( "activity_type_id" , 10 ) ;
        $s->where( 'deleted IS NULL [calc]', '[isBoolean]' ) ;
        $s->addField('time_end');
        $s->select() ;

        if ( $s->found ) {
            $timeEnd = $s->rows[0]->time_end;
            $jobEndTime = strtotime($jobEndTime);
            $userTimeEnd = strtotime($timeEnd);
            if ($userTimeEnd < $jobEndTime )
                return 1;
            else
                return 0;

        } else
            return 0;
    }

    public static function checkClockOff($jobID)
    {
        $s = new self() ;
        $s->where( "job_id" , $jobID) ;
        $s->where( "activity_type_id" , 10 ) ;
        $s->where( 'deleted IS NULL [calc]', '[isBoolean]' ) ;
        $s->select() ;

        if ( $s->found )
            return 1;
        else
            return 0;
    }

}



