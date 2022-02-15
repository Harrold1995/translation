<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class addComments extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'comments' );
	}


    public static function add( $refNum,  $staffID, $staffName, $comment)
    {
        $s = new self() ;
        $s->setField('ref_num', $refNum);
        $s->setField('staff_id', $staffID);
        $s->setField('staff_name', $staffName);
        $s->setField('comment', $comment);
        $s->insert() ;
    }

}



