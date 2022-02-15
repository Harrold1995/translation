<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class checkClockOn extends standardSqlRequest
{

	private $interpreter_id;

	function __construct()
	{
        parent::__construct( 'activities' );
	}

    public static function checkIfClockedOn( $jobID )
    {
        $s = new self() ;
        //$s->where( " date(created) =  curdate() [calc]" , '[isBoolean]' ) ;
        $s->where( "job_id" , $jobID ) ;
        $s->where( "activity_type_id" , 9) ; //check for logged on ==9 is the activity id for logged on
        $s->where( 'deleted IS NULL [calc]', '[isBoolean]' ) ;
        $s->select() ;
        //echo $s->sql;
        $data = (count($s->rows) > 0 ) ? true : false;
        return $data;
    }

    public static function getCurrentTime( $state )
    {
        $s = new self() ;
        $date = date("Y-m-d H:i:s");
        $queryString = "SELECT TIME_TO_SEC(DATE_FORMAT(UTC_to_TS('$state', '$date'), '%H:%i:%s')) AS curr_time";
        $result = $s->query($queryString);
        $data = $result->fetch_object();
        return $data->curr_time;

    }

}



