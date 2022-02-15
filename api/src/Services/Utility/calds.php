<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest;

class calds extends standardSqlRequest
{

	function __construct()
	{
        parent::__construct( 'nesbs' );
	}

    public static function getCaldRefNum($caldID)
    {
        $s = new self() ;
        $s->where( 'nesb_id' , $caldID) ;
        $s->addField('Ref Num', 'nesbs', 'refNum');
        $s->select();

        return $s->rows[0];
    }


    public static function getCald($caldID)
    {
        $s = new self() ;
        $s->where( 'nesb_id' , $caldID) ;
        $s->addField('nesb_id', 'nesbs', 'caldID');
        $s->addField('name');
        $s->addField('reference');
        $s->addField('gender');
        $s->addField('start_time');
        $s->addField('location');
        $s->addField('contact');
        $s->addField('professional');
        $s->select();

        return $s->rows[0];
    }
    public static function getCalds( $refNum)
    {
        $s = new self() ;
        $s->where( 'Ref Num' , $refNum) ;
        $s->addField('nesb_id', 'nesbs', 'caldID');
        $s->addField('name');
        $s->addField('reference');
        $s->addField('gender');
        $s->addField('start_time');
        $s->addField('location');
        $s->addField('contact');
        $s->addField('professional');
        $s->select();

        return $s->rows;
    }

    public static function add($refNum, $caldName, $reference, $gender, $startTime, $location, $contact, $professional)
    {
        $dataCount = self::checkIfExisting($caldName, $refNum, $reference, $startTime, $location);
        $data = [];
        if ($dataCount > 0)
        {
            $data['error'] = true;
            $data['message'] = "Invalid cald name, already listed.";
            $data['caldID'] = 0;
        } else
        {
            $s = new self();
            $s->setField('Ref Num', $refNum);
            $s->setField('name', $caldName);
            $s->setField('reference', $reference);
            $s->setField('gender', $gender);
            $s->setField('start_time', $startTime);
            $s->setField('location', $location);
            $s->setField('contact', $contact);
            $s->setField('professional', $professional);
            $s->insert();

            $data['error'] = false;
            $data['message'] = "";
            $data['caldID'] = $s->last_insert_id;
        }
        return $data;
    }

    public static function remove( $refNum, $caldID)
    {
        $s = new self() ;
        $s->where( 'Ref Num' , $refNum) ;
        $s->where( 'nesb_id' , $caldID) ;
        $s->delete();

    }

    public static function edit( $refNum, $caldID, $caldName, $reference, $gender, $startTime, $location, $contact, $professional)
    {
        $dataCount = self::checkIfExisting($caldName, $refNum, $reference, $startTime, $location, $caldID);
        $data = [];
        if ($dataCount > 0)
        {
            $data['error'] = true;
            $data['message'] = "Invalid cald name, already listed.";
        } else {
            $s = new self() ;
            $s->where( 'Ref Num' , $refNum) ;
            $s->where( 'nesb_id' , $caldID) ;

            if ($caldName != '')
                $s->setField('name', $caldName);

            if ($reference != '')
                $s->setField('reference', $reference);

            if ($gender != '')
                $s->setField('gender', $gender);

            if ($startTime != '')
                $s->setField('start_time', $startTime);

            if ($location != '')
                $s->setField('location', $location);

            if ($contact != '')
                $s->setField('contact', $contact);

            if ($professional != '')
                $s->setField('professional', $professional);

            $s->update();

            $data['error'] = false;
            $data['message'] = "";

        }
        return $data;

    }

    private static function checkIfExisting($caldName, $refNum, $reference, $startTime, $location, $caldID = 0)
    {
        $check = new self() ;
        $check->where( 'Ref Num' , $refNum) ;
        $check->where( 'name' , $caldName) ;
        $check->where( 'Reference' , $reference) ;
        $check->where( 'location' , $location) ;
        $check->where( 'start_time' , $startTime) ;

        if ($caldID != 0)
            $check->where( 'nesb_id' , $caldID, '!=') ;

        $check->addCount();
        $check->select();
        return $check->rows[0]->num;
    }

    public static function checkMultipleLocations($refNum) {
        $check = new self() ;
        $check->where( 'Ref Num' , $refNum) ;
        $check->where( 'location' , '', '!=') ;
        $check->addFieldCalc( 'num' , 'count( distinct location )' );
        $check->select();
        return $check->rows[0]->num;

    }

}



