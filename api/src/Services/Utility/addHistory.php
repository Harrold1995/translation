<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class addHistory extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'history' );
	}


    public static function add( $refNum, $action, $userID, $userName, $description, $notes)
    {
        $s = new self() ;
        $s->setField('refnum', $refNum);
        $s->setField('action', $action);
        $s->setField('user_id', $userID);
        $s->setField('user_name', $userName);
        $s->setField('description', $description);
        $s->setField('notes', $notes);
        $s->insert() ;
    }

}



