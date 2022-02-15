<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;
use \Datetime;

class nesbs extends standardSqlRequest
{

	private $interpreter_id;

	function __construct()
	{
        parent::__construct( 'nesbs' );
	}

    public static function createRecord( $data )
    {
        $s = new self() ;
        //create job record
        $s->setField( 'Ref Num' , $data['refNo']);
        $s->setField( 'Name' , $data['name']);
        $s->setField( 'Reference' , $data['reference']);
        $s->setField( 'location' , $data['location']);
        $s->setField( 'contact' , $data['contact']);
        $s->setField( 'professional' , $data['professional']);

        //validate time
        $caldStartTime = $data['startTime'];
        $validateStartTime = DateTime::createFromFormat("H:i:s", $caldStartTime);
        if ($validateStartTime !== false)
            $s->setField( 'start_time' , $data['startTime']);

        $s->insert() ;
    }


}



