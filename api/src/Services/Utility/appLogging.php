<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;

use API\sql\request as standardSqlRequest ;

class appLogging extends standardSqlRequest
{

	function __construct()
	{
        //parent::__construct( 'centrelink_app_monitoring' );
	}

    public static function createLog($request, $process, $contactID = '') {
	    if ($contactID == '') {
            $data = $request->getAttribute( 'data' );
            $contactID = $data->contactid;
        }

        if ($contactID != '') { //create log if there is contact id
            $requestParams = json_encode($request->getParams());
            $urlString = $request->getUri()->getPath();
            //appLogging::insertLog($requestParams, $process, $urlString, $contactID);

            //get what table to search for based on the invoice number
            $log = new standardSQLrequest( 'app_usage_monitoring' ) ;
            $log->setField('request_data', $requestParams);
            $log->setField('api_request', $process);
            //$log->setField('url_string', $request->getUri()->getPath());
            $log->setField('url_string', $urlString);
            $log->setField('contact_id', $contactID );
            $log->insert();

        }

    }




}

