<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class actionItems extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'action_items' );
	}

    public static function add( $refNum, $email, $intID, $clientID, $comments, $action = 'cancellation', $staffID = 208, $commentBy = 'API User')
    {
        $dataCount = self::checkItem($refNum, $intID, $action);
        $s = new self();
        if ($dataCount == 0) {
            $s->setField('ref_num', $refNum);
            $s->setField('staff_id', $staffID);
            $s->setField('comments', $comments);
            $s->setField('comment_by', $commentBy);
            $s->setField('interpreter_id', $intID);
            $s->setField('client_id', $clientID);
            $s->setField('email', $email);
            $s->setField('action', $action);
            $s->insert();
        } else {
            //get old comment
            $oldComment = self::getComment($refNum, $intID);
            $newComments = $oldComment . "<br>" . $comments;

            //update comment on action items
            $s->where('ref_num', $refNum);
            $s->where('interpreter_id', $intID);
            $s->setField('comments', $newComments);
            $s->update();
        }

        //echo $s->sql;
        //return $s->rows;
    }

    private static function checkItem($refNum, $intID, $action) {
        $s = new self() ;
        $s->where('action', $action);
        $s->where('ref_num', $refNum);
        $s->where('interpreter_id', $intID);
        $s->addCount();
        $s->select();
        return $s->rows[0]->num;

    }

    private static function getComment($refNum, $intID) {
        $s = new self() ;
        $s->where('ref_num', $refNum);
        $s->where('interpreter_id', $intID);
        $s->addField('comments');
        $s->select();

        //echo $s->sql;
        return $s->rows[0]->comments;

    }


}



