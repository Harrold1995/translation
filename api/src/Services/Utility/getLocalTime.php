<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class getLocalTime extends standardSqlRequest
{
	function __construct()
	{

	}

    public static function get( $time, $state )
    {
        $s = new self() ;
        //get current date
        $currentDate = date("Y-m-d");
        $queryString = "SELECT TS_to_UTC('$state', '$currentDate $time') AS converted_time;";
        $result = $s->query($queryString);
        $clients = array();
        $data = $result->fetch_object();
        return $data->converted_time;
    }
}

