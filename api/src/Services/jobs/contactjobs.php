<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 12/12/16
 * Time: 11:28 AM
 */

namespace API\jobs;

use API\functions\debug;
use API\sql\request as standardSqlRequest;
use API\utility\checkLanguage as checkLanguage;
use API\utility\checkLocation as checkLocation;
use API\utility\addHistory as addHistory;
use API\utility\addComments as addComments;
use API\utility\calds as calds;
use API\utility\actionItems as actionItems;
use API\utility\interpreter as interpreter;

use API\utility\nesbs as nesbs;
use API\utility\num as num;
use API\jwt\token as token;
use Slim\Http\Response;
use Slim\Http\Request;
use \Datetime;



class contactjobs extends standardSqlRequest
{

    function __construct($container = '')
    {
        parent::__construct('jobs');
        $this->fetchObjParams = [$container];
        //$this->fetchObj = jobobj::class;
    }

    private function getCaldsFields()
    {
        //get calds
        $subq = $this->retSubQuery( 'calds' , 'nesbs' );
        $subq->where( 'Ref Num' , 'jobs.`Ref No`[calc]' );
        $leftBrace = "'{'" ;
        $rightBrace = "'}'" ;
        $nameLabel = "'\"name\":\"', nesbs.Name , '\"'";
        $refNumLabel = "',\"refNum\":\"' , nesbs.`Ref Num` , '\"'";
        $modifiedLabel = "',\"bookingUpdated\":\"' , nesbs.modified , '\"'";
        $refLabel = "',\"reference\":\"' , nesbs.Reference , '\"'";
        $timeLabel = "',\"start_time\":\"' , ifnull( DATE_FORMAT(nesbs.start_time, '%l:%i %p') , '' ) , '\"'";
        $locLabel = "',\"location\":\"' , ifnull( nesbs.location , '' ) , '\"'";
        $singleCald = "concat( $leftBrace, $nameLabel, $refNumLabel, $modifiedLabel, $refLabel, $timeLabel, $locLabel, $rightBrace )";
        $group = "GROUP_CONCAT( $singleCald ORDER BY start_time  )";
        $putItAllTogether = "concat( '" . '[' . "' , $group , '" . ']' . "' )";
        $fld = $subq->addFieldAlias( 'calds' );
        $fld->setCalc( $putItAllTogether );

    }

    private function listStandard()
    {
        $this->addFieldAlias( 'service_date' , 'Service Date' );
        $this->addFieldAlias( 'job_type' , 'Inter trans' );
        $this->addFieldAlias( 'client_name' , 'Invoice To' );
        $this->addFieldAlias( 'ref_num' , 'Ref No' );
        $this->addFieldAlias( 'job_id' , 'id' );
        $this->addFieldAlias( 'int_id' , 'INT ID' );
        $this->addFieldAlias('interpreter_first_name', 'Interpreters Name');
        $this->addFieldAlias('interpreter_last_name', 'Interpreters Surname');
        $this->addFieldAlias( 'language' , 'Language' );
        $this->addField( 'location_name' );
        $this->addField( 'location_address_1' );
        $this->addField( 'location_address_2' );
        $this->addField( 'location_suburb' );
        $this->addField( 'location_postcode' );
        $this->addSort( 'service_date' , 'asc' );
        $this->addSort( 'start' , 'asc'  );
        $this->addFieldCalc( 'start' , "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%l:%i %p' )" );
        $this->addFieldCalc( 'end' , "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%l:%i %p' )" );
        $this->addLeftJoin( 'interpreters' , 'INT ID' , 'fm_interpreter_id' );
    }

    public function getInvoicedJobs(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Get Invoiced Bookings', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        //$invoiceNumber = $request->getParsedBodyParam( 'invoiceNumber' );
        //$invoiceNumber = $request->getQueryParam( 'invoiceNumber' );
        //$invoiceNumber = $request->getParam( 'invoiceNumber' );
        $invoiceNumber = $args['id'];


        //get what table to search for based on the invoice number
        $invoiceLines = new standardSQLrequest( 'invoice_lines' ) ;
        $invoiceLines->addFieldCalc('dataCount', 'COUNT(*)');
        $invoiceLines->where( 'invoice_number' , $invoiceNumber);
        $invoiceLines->select();
        $invoiceLinesCount = $invoiceLines->rows[0]->dataCount;

        //$invoiceLinesAggregates = new standardSQLrequest( 'invoice_lines_aggregates' ) ;
        //$invoiceLinesAggregates->addFieldCalc('dataCount', 'COUNT(*)');
        //$invoiceLinesAggregates->where( 'aggregate_invoice_id' , $invoiceNumber);
        //$invoiceLinesAggregates->select();
        //$invoiceLinesAggregatesCount = $invoiceLinesAggregates->rows[0]->dataCount;


        $this->where( 'Client ID', $clientID );
        $this->listStandard();
        $this->addFieldCalc( 'fee' , "IFNULL(gst_inc(sub_total + surcharge + travel_cost), 0)" );
        if ($invoiceLinesCount > 0)
        {
            $this->addJoin( 'invoice_lines' , 'ref no', 'ref_num' );
            $this->where('invoice_number', $invoiceNumber, '=', 'invoice_lines');
            $this->addField('invoice_number', 'invoice_lines');

            $this->addLeftJoin( 'invoices' , 'invoice_number', 'invoice_number', 'invoice_lines');
            $this->addField('invoice_date', 'invoices');

        } else
        {
            $this->addJoin( 'invoice_lines_aggregate' , 'id', 'job_id' );
            $this->where( 'aggregate_invoice_id', $invoiceNumber, '=', 'invoice_lines_aggregate');
            $this->addField('aggregate_invoice_id', 'invoice_lines_aggregate', 'invoice_number');

            $this->addLeftJoin( 'invoices_aggregate' , 'aggregate_invoice_id', 'aggregate_invoice_id', 'invoice_lines_aggregate');
            $this->addField('created', 'invoices_aggregate', 'invoice_date');

        }
        $subq = $this->retSubQuery( 'calds' , 'nesbs' );
        $subq->where( 'Ref Num' , 'jobs.`Ref No`[calc]' );
        $leftBrace = "'{'" ;
        $rightBrace = "'}'" ;
        $nameLabel = "'\"name\":\"', nesbs.Name , '\"'";
        $refLabel = "',\"reference\":\"' , nesbs.Reference , '\"'";
        $singleCald = "concat( $leftBrace, $nameLabel, $refLabel, $rightBrace )";
        $group = "GROUP_CONCAT( $singleCald ORDER BY start_time  )";
        $putItAllTogether = "concat( '" . '[' . "' , $group , '" . ']' . "' )";
        $fld = $subq->addFieldAlias( 'calds' );
        $fld->setCalc( $putItAllTogether );


        $this->select();
        //echo $this->sql;
        //print_r($this->rows);
        $obj = (object)['error' => false, 'data' => $this->rows];
        return $response->withJson( $obj );
    }

    public function getOutstandingJobs(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Get Outstanding Bookings', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        //$baseDate = $request->getParsedBodyParam( 'baseDate' );
        $dateFrom = $request->getParam( 'dateFrom' );
        $validateDate = DateTime::createFromFormat("Y-m-d", $dateFrom);
        if ($validateDate === false) {
            $obj = (object)['error' => true, 'message' => 'Invalid date'];
        } else
        {
            $this->where( "jobs.`Service Date` >= '$dateFrom' [calc]", '[isBoolean]' ) ;
            $this->where( 'Client ID', $clientID );
            $this->listStandard();
            //get calds
            $this->getCaldsFields();
            $this->select();
            //echo $this->sql;
            //exit;
            $obj = (object)['error' => false, 'data' => $this->rows];
        }
        return $response->withJson( $obj );
    }


    public function getUpdatedJobs(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Get Updated Bookings', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        //$baseDate = $request->getParsedBodyParam( 'baseDate' );
        $dateFrom = $request->getParam( 'dateFrom' );

        $validateDate = DateTime::createFromFormat("Y-m-d", $dateFrom);
        if ($validateDate === false) {
            $obj = (object) [ 'error' => true, 'message' => 'Invalid date' ] ;
        } else {
            $this->where("jobs.modified >= '$dateFrom' [calc]", '[isBoolean]');
            $this->where('Client ID', $clientID);
            $this->listStandard();
            $this->addFieldCalc( 'not_serviced' , "IFNULL(not_serviced, 0)" );

            //get calds
            $this->getCaldsFields();
            $this->select();
            $obj = (object)['error' => false, 'data' => $this->rows];
        }
        return $response->withJson( $obj );

    }

    public function createJob(Request $request, Response $response, $args)
    {

        $allowedJobTypes = array("Video", "Trans", "Ph Int", "Inter");

        //check language if its existing
        $language = $request->getParsedBodyParam('language');
        $locationName = $request->getParsedBodyParam('location');
        $locationAddress = $request->getParsedBodyParam('locationAddress');
        $locationAddress2 = $request->getParsedBodyParam('locationAddress2');
        $locationSuburb = $request->getParsedBodyParam('locationSuburb');
        $locationPostcode = $request->getParsedBodyParam('locationPostcode');
        $professional = $request->getParsedBodyParam('professional');
        $serviceDate = $request->getParsedBodyParam('serviceDate');
        $startTime = $request->getParsedBodyParam('startTime');
        $endTime = $request->getParsedBodyParam('endTime');
        $requestedIntID = $request->getParsedBodyParam('requestedIntID');
        $requestedOnly = $request->getParsedBodyParam('requestedOnly');
        $languageID = $request->getParsedBodyParam('languageID');
        $locationID = $request->getParsedBodyParam('locationID');
        $jobType = $request->getParsedBodyParam('jobType'); //Inter trans
        $requestedBy = $request->getParsedBodyParam('requestedBy');
        $costCentre = $request->getParsedBodyParam('costCentre');
        $department = $request->getParsedBodyParam('department');
        $email = $request->getParsedBodyParam('email');
        $homeVisit = $request->getParsedBodyParam('homeVisit');
        $otherInfo = $request->getParsedBodyParam('otherInfo');
        $gender = strtoupper($request->getParsedBodyParam('gender'));
        $refNum = num::getNum();

        //get token data
        $tokenData = $request->getAttribute('data');
        $clientID = $tokenData->clientid;

        //validate email
        $validateEmail = true;
        if ($email != '')
            $validateEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        //validate locationID if it is set
        $validateLocation = true;
        if ($locationID != '')
            $validateLocation = checkLocation::validateLocation($locationID, $clientID); //check location id
        else {
            if ($locationName == '' || $locationAddress == '')
                $validateLocation = false;
        }


        //validate gender
        $allowedGenders = ["M", "F"];
        $validateGender = true;
        if ($gender != '') {
            if (!in_array($gender, $allowedGenders))
                $validateGender = false;
        }

        $validateRequestedOnly = true;
        if ($requestedOnly != '') {
            if (!in_array($requestedOnly, [0,1]))
                $validateRequestedOnly = false;
        }

        //validate language
        if ($languageID == '') {
            $validateLanguage = checkLanguage::validateLanguage($language);
        } else {
            $validateLanguage = checkLanguage::validateLanguageID($languageID);
        }

        $validateDate = DateTime::createFromFormat("Y-m-d", $serviceDate);
        $validateStartTime = DateTime::createFromFormat("H:i:s", $startTime);
        $validateEndTime = DateTime::createFromFormat("H:i:s", $endTime);

        if ($requestedIntID != '' && !interpreter::checkInterpreter($requestedIntID)) {
            $obj = (object)['error' => true, 'message' => 'Invalid Interpreter ID'];
        } else if ($homeVisit != "1" && $homeVisit != "0" && $homeVisit != "") {
            $obj = (object)['error' => true, 'message' => 'Invalid Home visit value'];
        } else if ($validateRequestedOnly == false) {
            $obj = (object)['error' => true, 'message' => 'Invalid Requested only value'];
        } else if ($validateGender == false) {
            $obj = (object)['error' => true, 'message' => 'Invalid Gender'];
        } else if ($validateEmail == false) {
            $obj = (object)['error' => true, 'message' => 'Invalid Email'];
        } else if ($validateLocation == false) {
            $obj = (object)['error' => true, 'message' => 'Invalid Location ID'];
        } else if (!in_array($jobType, $allowedJobTypes)) {
            $obj = (object)['error' => true, 'message' => 'Invalid Job Type'];
        } else if ($validateLanguage == false) {
            $obj = (object) [ 'error' => true, 'message' => 'Invalid language'];
        } else if ($serviceDate == '' || $startTime == '' || $endTime == '') {
            $obj = (object) [ 'error' => true, 'message' => 'Missing one of the required fields (date, start & end time)' ] ;
        } else if ($validateDate === false) {
            $obj = (object) [ 'error' => true, 'message' => 'Invalid date' ] ;
        } else if ($validateStartTime === false || $validateEndTime === false) {
            $obj = (object) [ 'error' => true, 'message' => 'Invalid start / end time' ] ;
        } else {
            //create job record
            $this->setField('Available Num', 0);
            $this->setField('Ref No', $refNum);
            $this->setField('Service Date', $serviceDate);
            $this->setField('Service Time start', $startTime);
            $this->setField('Service Time end', $endTime);
            //added 12/19/2017
            $this->setField('Booking Received by', 'API');


            if ($professional != '')
                $this->setField('Professional', $professional);

            $this->setField('Client ID', $clientID);
            $this->setField('Inter trans', $jobType);

            if ($requestedBy != '')
                $this->setField('Requested by', $requestedBy);

            if ($costCentre != '')
                $this->setField('Cost Centre', $costCentre);

            if ($department != '')
                $this->setField('Department', $department);

            if ($email != '')
                $this->setField('Email', $email);

            if ($homeVisit != '')
                $this->setField('home_visit', $homeVisit);

            if ($otherInfo != '')
                $this->setField('Other Info', $otherInfo);

            if ($gender != '')
                $this->setField('requested_gender', $gender);

            if ($requestedIntID != '')
                $this->setField('requested', $requestedIntID);

            if ($requestedOnly == '1')
                $this->setField('requested_only', $requestedOnly);


            if ($languageID != '')
                $this->setField('language id', $languageID);
            else
                $this->setField('language', $language);

            if ($locationID != '')
                $this->setField('location id', $locationID);
            else {
                $this->setField('location_address_1', $locationAddress);
                $this->setField('location_name', $locationName);
            }

            $this->insert();

            //echo $this->sql;
            if ($locationID != '' && ($locationAddress != '' || $locationAddress2 != '')) {
                $jobID = $this->last_insert_id;
                //clear set fields
                $this->clearSetFields();

                if ($locationName != '')
                    $this->setField('location_name', $locationName);

                if ($locationAddress != '')
                    $this->setField('location_address_1', $locationAddress);

                if ($locationAddress2 != '')
                    $this->setField('location_address_2', $locationAddress2);

                if ($locationSuburb != '')
                    $this->setField('location_suburb', $locationSuburb);

                if ($locationPostcode != '')
                    $this->setField('location_postcode', $locationPostcode);

                $this->where('id', $jobID);
                $this->update();
            }


            //add to history
            addHistory::add($refNum, 'API Create Booking', 10,  'API User', "Created Booking", '');

            actionItems::add($refNum, '', '', $clientID, "Created new booking: $refNum", 'api new');
            //add to comments
            //addComments::add( $refNum,  208, 'API User', "Created Booking");

            if ($otherInfo != '')
                addComments::add( $refNum,  208, 'API User', $otherInfo);


            $obj = (object)['error' => false, 'message' => '', 'ref_num' => $refNum];
        }

        //log api request
        $this->logAPIRequest('Create Booking', $request, $refNum);

        $response = $response->withJson( $obj ) ;
        return $response;

    }

    public function getJobEstimateCost(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Get Booking Estimate', $request);

        $data = $request->getAttribute( 'data' );
        $refNum = $args['id'];
        $obj = (object)['error' => false, 'message' => ''];
        if ( $jobid = details::exists( $refNum, false, $data->clientid ) )
        {
            $result = $this->query("SELECT gst_inc( client_fee_actual( '$refNum' ) ) AS fee");
            $records = $result->fetch_object();
            $obj->fee = ($records->fee =='' ) ? 0 : $records->fee;
            //print_r($records);
        } else
        {
            $obj = (object)['error' => true, 'message' => 'Invalid Reference number'];
            //gst_inc( client_fee_actual( `Ref No` ) )
        }
        return $response->withJson( $obj );
    }

    public function cancelJob(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Cancel Booking', $request);

        $data = $request->getAttribute( 'data' );
        $obj = (object)['error' => false, 'message' => ''];
        $refNum = $args['id'];
        $clientID = $data->clientid;
        if ( $jobid = details::exists( $refNum, false, $clientID ) )
        {
            $cancellationReason = $request->getParam('cancellationReason');
            $this->where('ref no', $refNum);
            $this->setField('cancellation', 'Yes');
            $this->setField('Cancellation Date', 'NOW() [calc]');
            $this->setField('Time of Cancellation', 'CURTIME() [calc]');
            $this->update();

            //add to history
            addHistory::add($refNum, 'API Update', 208,  'API User', "Cancelled Booking - $cancellationReason", '');

            //add to comments
            addComments::add( $refNum,  208, 'API User', "Cancelled Booking - $cancellationReason");

            //get details of a job
            $this->where('Ref No', $refNum);
            $this->addField('email');
            $this->addField('INT ID', 'jobs', 'intID');
            $this->select();

            $jobDetails = $this->rows[0];
            $email = $jobDetails->email;
            $intID = $jobDetails->intID;

            /*
             * new update if the interpreter is not set
             * on cancel we will set the interpreter to 008
             */
            if ($intID == '') {
                $cancelInterpreter = new standardSQLrequest('jobs');
                $cancelInterpreter->setField('INT ID', '008');
                $cancelInterpreter->where('ref no', $refNum);
                $cancelInterpreter->update();
            } else
                actionItems::add($refNum, $email, $intID,  $clientID, "Cancelled Booking Using API - $cancellationReason"); //if there is an interpreter assigned, add to action items


        } else {
            $obj->error = true;
            $obj->message = "Invalid Reference Number";
        }
        return $response->withJson( $obj );

    }

    public function updateJob(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Update Booking', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        $refNum = $args['id'];

        $obj = (object)['error' => false, 'message' => ''];
        if ( $jobid = details::exists( $refNum , false, $data->clientid ) )
        {
            $allowedJobTypes = array("Video", "Trans", "Ph Int", "Inter");
            //check language if its existing
            $language = $request->getParam( 'language' );
            $locationName = $request->getParam( 'location' );
            $locationAddress = $request->getParam( 'locationAddress' );
            $locationAddress2 = $request->getParam( 'locationAddress2' );
            $locationSuburb = $request->getParam( 'locationSuburb' );
            $locationPostcode = $request->getParam( 'locationPostcode' );
            $professional = $request->getParam( 'professional' );
            $serviceDate = $request->getParam( 'serviceDate' );
            $startTime = $request->getParam( 'startTime' );
            $endTime = $request->getParam( 'endTime' );
            $requestedIntID = $request->getParam( 'requestedIntID' );
            $requestedOnly = $request->getParam( 'requestedOnly' );
            $languageID = $request->getParam( 'languageID' );
            $locationID = $request->getParam( 'locationID' );
            $requestedBy = $request->getParam( 'requestedBy' );
            $costCentre = $request->getParam( 'costCentre' );
            $department = $request->getParam( 'department' );
            $email = $request->getParam( 'email' );
            $homeVisit = $request->getParam( 'homeVisit' );
            $otherInfo = $request->getParam( 'otherInfo' );
            $gender = $request->getParam( 'gender' );

            //validate gender
            $allowedGenders = ["M", "F"];
            $validateGender = true;
            if ($gender != '') {
                if (!in_array($gender, $allowedGenders))
                    $validateGender = false;
            }

            $validateRequestedOnly = true;
            if ($requestedOnly != '') {
                if (!in_array($requestedOnly, [0,1]))
                    $validateRequestedOnly = false;
            }


            if ($serviceDate != '') {
                $obj->error = true;
                $obj->message = "Service date is not allowed to be updated, please cancel the booking and create a new booking with the new date.";
            } else {
                //validate email
                $validateEmail = true;
                if ($email != '')
                    $validateEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

                //validate locationID if it is set
                $validateLocation = true;
                if ($locationID != '')
                    $validateLocation = checkLocation::validateLocation($locationID, $clientID); //check location id
                else {
                    if ($locationName == '' || $locationAddress == '')
                        $validateLocation = false;
                }

                if ($locationName == '' && $locationAddress == '' && $locationID == '')
                    $validateLocation = true;


                //validate language
                if ($languageID == '') {
                    $validateLanguage = checkLanguage::validateLanguage($language);
                } else {
                    $validateLanguage = checkLanguage::validateLanguageID($languageID);
                }

                if ($language == '' && $languageID == '')
                    $validateLanguage = true;


                $validateDate = DateTime::createFromFormat("Y-m-d", $serviceDate);
                $validateStartTime = DateTime::createFromFormat("H:i:s", $startTime);
                $validateEndTime = DateTime::createFromFormat("H:i:s", $endTime);

                if ($requestedIntID != '' && !interpreter::checkInterpreter($requestedIntID)) {
                    $obj = (object)['error' => true, 'message' => 'Invalid Interpreter ID'];
                } else if ($homeVisit != "1" && $homeVisit != "0" && $homeVisit != "") {
                    $obj = (object)['error' => true, 'message' => 'Invalid Home visit value'];
                } else if ($validateRequestedOnly == false) {
                    $obj = (object)['error' => true, 'message' => 'Invalid Requested only value'];
                } else if ($validateGender == false) {
                    $obj = (object)['error' => true, 'message' => 'Invalid Gender'];
                } else if ($validateEmail == false) {
                    $obj = (object)['error' => true, 'message' => 'Invalid Email'];
                } else if ($validateLocation == false) {
                    $obj = (object)['error' => true, 'message' => 'Invalid Location ID'];
                } else if ($validateLanguage == false) {
                    $obj = (object)['error' => true, 'message' => 'Invalid language'];
                } else if ($validateDate === false && $serviceDate != "") {
                    $obj = (object)['error' => true, 'message' => 'Invalid date'];
                } else if ($validateStartTime === false && $startTime != "") {
                    $obj = (object)['error' => true, 'message' => 'Invalid start time'];
                } else if ($validateEndTime === false && $endTime != "") {
                    $obj = (object)['error' => true, 'message' => 'Invalid end time'];
                } else {

                    //create job record
                    $this->where('Ref No', $refNum);

                    if ($serviceDate != '')
                        $this->setField('Service Date', $serviceDate);


                    /*get old details of the booking
                     * get postcode and service date
                     * compute the date for service start and service end
                     */
                    $oldDetails = new standardSQLrequest( 'jobs' ) ;
                    $oldDetails->addField('postcode', 'clients');
                    $oldDetails->addField('Service Date', 'jobs', 'serviceDate');
                    $oldDetails->where( 'Ref No' , $refNum );
                    $oldDetails->addLeftJoin('clients', 'Client ID');
                    $oldDetails->select();

                    $postal = $oldDetails->rows[0]->postcode;
                    $oldServiceDate = $oldDetails->rows[0]->serviceDate;

                    if ($startTime != '') {
                        $this->setField('Service Time start', $startTime);
                        $this->setField('actual_start_time', $startTime);
                        $this->setField('service_start', "TS_to_UTC( state_from_postcode( $postal ) , TIMESTAMP( '$oldServiceDate' , '$startTime' )) [calc]");
                    }

                    if ($endTime != '') {
                        $this->setField('Service Time end', $endTime);
                        $this->setField('actual_end_time', $endTime);
                        $this->setField('service_end', "TS_to_UTC( state_from_postcode( $postal ) , TIMESTAMP( '$oldServiceDate' , '$endTime' )) [calc]");
                    }

                    if ($professional != '')
                        $this->setField('Professional', $professional);

                    if ($clientID != '')
                        $this->setField('Client ID', $clientID);

                    if ($requestedBy != '')
                        $this->setField('Requested by', $requestedBy);

                    if ($costCentre != '')
                        $this->setField('Cost Centre', $costCentre);

                    if ($department != '')
                        $this->setField('Department', $department);

                    if ($email != '')
                        $this->setField('Email', $email);

                    if ($homeVisit != '')
                        $this->setField('home_visit', $homeVisit);

                    if ($otherInfo != '')
                        $this->setField('Other Info', $otherInfo);

                    if ($gender != '')
                        $this->setField('requested_gender', $gender);

                    if ($requestedIntID != '')
                        $this->setField('requested', $requestedIntID);

                    if ($requestedOnly != '') {
                        $requestedOnly = ($requestedOnly == 1) ? 1 : 'NULL [calc]';
                        $this->setField('requested_only', $requestedOnly);
                    }

                    if ($languageID != '')
                        $this->setField('language id', $languageID);
                    else {
                        if ($language != '')
                            $this->setField('language', $language);
                    }

                    if ($locationID != '') {
                        $this->setField('location_name', '');
                        $this->setField('location id','');
                    } else {
                        if ($locationAddress != '')
                            $this->setField('location_address_1', $locationAddress);

                        if ($locationName != '')
                            $this->setField('location_name', $locationName);
                    }

                    //get old details of the booking
                    $jobDetails = $this->getJobDetails($refNum);

                    //first update to clear the location if there is a location id
                    $this->update();


                    if ($locationID != '') {
                        $locationUpdate = new standardSQLrequest( 'jobs' ) ;

                        //update location id first
                        $locationUpdate->setField('location id', $locationID);
                        $locationUpdate->where('Ref No', $refNum);
                        $locationUpdate->update();

                        //$this->setField('location_name', '');
                        $locationUpdate2 = new standardSQLrequest( 'jobs' ) ;
                        if ($locationName != '')
                            $locationUpdate2->setField('location_name', $locationName);

                        if ($locationAddress != '')
                            $locationUpdate2->setField('location_address_1', $locationAddress);

                        if ($locationAddress2 != '')
                            $locationUpdate2->setField('location_address_2', $locationAddress2);

                        if ($locationSuburb != '')
                            $locationUpdate2->setField('location_suburb', $locationSuburb);

                        if ($locationPostcode != '')
                            $locationUpdate2->setField('location_postcode', $locationPostcode);

                        $locationUpdate2->where('Ref No', $refNum);
                        $locationUpdate2->update();
                    }

                    //add to history
                    addHistory::add($refNum, 'API Update Booking details', 208, 'API User', "Updated Booking", '');

                    //get interpreter id of job
                    $intID = $this->getIntID($refNum);

                    $updatedDetails = "";
                    $oldTimeStart = date("G:i:s", strtotime($jobDetails->service_time_start));
                    $oldTimeEnd = date("G:i:s", strtotime($jobDetails->service_time_end));

                    if ($startTime != '' && $oldTimeStart != $startTime)
                        $updatedDetails .= "Changed service start time from " . $oldTimeStart . " to " . $startTime. ". <br>";

                    if ($endTime != '' && $oldTimeEnd != $endTime)
                        $updatedDetails .= "Changed service end time from " . $oldTimeEnd . " to " . $endTime. ". <br>";

                    if ($locationID != '' && $jobDetails->locationID != $locationID)
                        $updatedDetails .= "Changed location ID from " . $jobDetails->$locationID . " to " . $locationID. ". <br>";

                    if ($locationAddress != '' && $jobDetails->location_address_1 != $locationAddress)
                        $updatedDetails .= "Changed location address1 from " . $jobDetails->location_address_1 . " to " . $locationAddress. ". <br>";

                    if ($locationAddress2 != '' && $jobDetails->location_address_2 != $locationAddress2)
                        $updatedDetails .= "Changed location address2 from " . $jobDetails->location_address_2 . " to " . $locationAddress2. ". <br>";

                    if ($locationID == '' && $locationName != '' && $jobDetails->location_name != $locationName)
                        $updatedDetails .= "Changed location name from " . $jobDetails->location_name . " to " . $locationName. ". <br>";

                    if ($intID != '' && ($startTime != '' || $endTime != '' || $locationName != '' || $locationID != '' || $locationAddress != '' || $locationAddress2 != '' || $locationName != '') ) {
                        //add to action items
                        actionItems::add($refNum, '', $intID, $clientID, "Updated Booking details. $updatedDetails", 'follow up');
                    }



                    if ($updatedDetails != '') {
                        //add to comments
                        $updatedDetails = str_replace("<br>", " ", $updatedDetails);
                        $commentIfo = "Updated Booking details. $updatedDetails";
                        addComments::add($refNum, 208, 'API User', $commentIfo);
                    }

                    $obj = (object)['error' => false, 'message' => '', 'ref_num' => $refNum];
                }
            }
        } else {
            $obj->error = true;
            $obj->message = "Invalid Reference Number";
        }
        return $response->withJson( $obj );

    }

    public function searchInterpreters(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Search Interpreters', $request);

        $interpreterName = $request->getParam( 'interpreterName' );
        $languageID = $request->getParam( 'languageID' );


        $interpreterName = str_replace(" ", "%", $interpreterName);
        $interpreterName = "%$interpreterName%";

        $interpreters = interpreter::searchInterpreter($languageID, $interpreterName);

        $obj = (object)['error' => false, 'found' => count($interpreters), 'data' => $interpreters];
        return $response->withJson( $obj );
    }

    public function getCalds(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Get Calds', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        $refNum = $args['id'];
        $obj = (object)['error' => false, 'message' => ''];

        if ( $jobid = details::exists( $refNum , false, $clientID ) )
        {
            $calds = calds::getCalds($refNum);

            //echo $this->sql;
            $obj->error = false;
            $obj->data = $calds;
            $obj->found = count($calds);

        } else {
            $obj->error = true;
            $obj->message = "Invalid Reference Number";
        }
        return $response->withJson( $obj );

    }

    public function getCald(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Get Cald', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        $caldID = $args['id'];

        $obj = (object)['error' => false, 'message' => ''];
        //get reference number
        $caldDetails = calds::getCaldRefNum($caldID);
        if (!isset($caldDetails->refNum)) {
            $obj->error = true;
            $obj->message = "Cald Not Found";
            $obj->found = 0;
        } else {
            $refNum = $caldDetails->refNum;
            if ( $jobid = details::exists( $refNum , false, $clientID ) )
            {
                $calds = calds::getCald($caldID);

                //echo $this->sql;
                $obj->error = false;
                $obj->data = $calds;
                $obj->found = count($calds);

            } else {
                $obj->error = true;
                $obj->message = "Invalid Cald Details";
            }
        }
        return $response->withJson( $obj );

    }
    public function addCald(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Add Calds', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        $refNum = $args['id'];

        $caldName = $request->getParsedBodyParam( 'caldName' );
        $reference = $request->getParsedBodyParam( 'reference' );
        $gender = $request->getParsedBodyParam( 'gender' );
        $startTime = $request->getParsedBodyParam( 'startTime' );
        $location = $request->getParsedBodyParam( 'location' );
        $contact = $request->getParsedBodyParam( 'contact' );
        $professional = $request->getParsedBodyParam( 'professional' );

        $validateTime = DateTime::createFromFormat("H:i:s", $startTime);
        if ($validateTime === false) {
            $obj = (object)['error' => true, 'message' => 'Invalid start time'];
        } else
        {
            $obj = (object)['error' => false, 'message' => ''];
            if ( $jobid = details::exists( $refNum , false, $clientID ) )
            {
                $result = calds::add($refNum, $caldName, $reference, $gender, $startTime, $location, $contact, $professional);
                if ($result['error'] == 1) {
                    $obj->error = true;
                    $obj->message = $result['message'];
                } else {

                    //echo $this->sql;
                    $obj->error = false;
                    $obj->cald_id = $result['caldID'];
                    $obj->data = calds::getCald($result['caldID']);


                    //add to history
                    addHistory::add($refNum, 'API Added Cald', 208, 'API User', "Added Cald - calID: " .  $obj->cald_id, '');

                    //get interpreter id of job
                    //$intID = $this->getIntID($refNum);
                    $jobDetails = $this->getJobDetails($refNum);
                    $intID = $jobDetails->int_id;



                    $updatedDetails = "";

                    if ($caldName != '')
                        $updatedDetails .= "Patient " . $caldName . " was added.<br>";

                    if ($startTime != '') {
                        $startTime = date("g:i A", strtotime($startTime));
                        $updatedDetails .= "Start time: " . $startTime . ".<br>";
                    }

                    if ($reference != '')
                        $updatedDetails .= "Reference: " . $reference . ".<br>";

                    if ($location != '')
                        $updatedDetails .= "Location: " . $location . ".<br>";

                    if ($contact != '')
                        $updatedDetails .= "Contact: " . $contact . ".<br>";

                    if ($gender != '')
                        $updatedDetails .= "Gender: " . $gender . ".<br>";

                    if ($professional != '')
                        $updatedDetails .= "Professional: " . $professional . ".<br>";

                    //add location for the job
                    $updatedDetails .= "Service Date: " . $jobDetails->service_date . ".<br>";
                    $updatedDetails .= "Start time: " . $jobDetails->service_time_start . ".<br>";
                    $updatedDetails .= "Start end: " . $jobDetails->service_time_end . ".<br>";
                    $updatedDetails .= "Location: " . $jobDetails->location_name . ".<br>";

                    if ($intID != '') {
                        //add to action items
                        actionItems::add($refNum, '', $intID, $clientID, "Added Cald. $updatedDetails", 'follow up');
                    }

                    if ($updatedDetails != '') {
                        //add to comments
                        $updatedDetails = str_replace("<br>", " ", $updatedDetails);
                        $commentIfo = "Added Cald. $updatedDetails";
                        addComments::add($refNum, 208, 'API User', $commentIfo);
                    }

                }
                //check if location for patient is multiple
                $this->updateJobLocation($refNum);

            } else {
                $obj->error = true;
                $obj->message = "Invalid Reference Number";
            }
        }
        return $response->withJson( $obj );

    }

    public function updateCald(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Update Cald Details', $request);

        $data = $request->getAttribute( 'data' );
        $clientID = $data->clientid;
        $caldID = $args['id'];

        //$caldID = $request->getParam( 'caldID' );
        $caldName = $request->getParam( 'caldName' );
        $reference = $request->getParam( 'reference' );
        $gender = $request->getParam( 'gender' );
        $startTime = $request->getParam( 'startTime' );
        $location = $request->getParam( 'location' );
        $contact = $request->getParam( 'contact' );
        $professional = $request->getParam( 'professional' );

        $obj = (object)['error' => false, 'message' => ''];
        //get reference number
        $caldDetails = calds::getCaldRefNum($caldID);
        if (!isset($caldDetails->refNum)) {
            $obj->error = true;
            $obj->message = "Cald Not Found";
            $obj->found = 0;
        } else {
            $refNum = $caldDetails->refNum;
            $validateTime = DateTime::createFromFormat("H:i:s", $startTime);
            if ($validateTime === false) {
                $obj = (object)['error' => true, 'message' => 'Invalid start time'];
            } else {
                //check name if it exists
                if ($jobid = details::exists($refNum, false, $clientID)) {
                    //get old cald details
                    $caldOldDetails = calds::getCald($caldID);
                    $result = calds::edit($refNum, $caldID, $caldName, $reference, $gender, $startTime, $location, $contact, $professional);
                    if ($result['error'] == true) {
                        $obj->error = true;
                        $obj->message = $result['message'];
                    } else {
                        //add to history
                        addHistory::add($refNum, 'API Updated Cald', 208, 'API User', "Updated Cald Info - caldID: $caldID", '');

                        //get interpreter id of job
                        //$intID = $this->getIntID($refNum);
                        $jobDetails = $this->getJobDetails($refNum);
                        $intID = $jobDetails->int_id;


                        //get cald details

                        $updatedDetails = "";

                        if ($caldName != '' && $caldOldDetails->name != $caldName)
                            $updatedDetails .= "Name was updated from " . $caldOldDetails->name . " to " . $caldName . ".<br>";

                        if ($startTime != '' && $caldOldDetails->start_time != $startTime) {
                            $startTime = date("g:i A", strtotime($startTime));
                            $updatedDetails .= "Start time was updated from " . $caldOldDetails->start_time . " to " . $startTime . ".<br>";
                        }

                        if ($reference != '' && $caldOldDetails->reference != $reference)
                            $updatedDetails .= "Reference was updated from " . $caldOldDetails->reference . " to " . $reference . ".<br>";

                        if ($location != '' && $caldOldDetails->location != $location)
                            $updatedDetails .= "Location was updated from " . $caldOldDetails->location . " to " . $location . ".<br>";

                        if ($gender != '' && $caldOldDetails->gender != $gender)
                            $updatedDetails .= "Gender was updated from " . $caldOldDetails->gender . " to " . $gender . ".<br>";

                        if ($contact != '' && $caldOldDetails->contact != $contact)
                            $updatedDetails .= "Contact was updated from " . $caldOldDetails->contact . " to " . $contact . ".<br>";

                        if ($professional != '' && $caldOldDetails->professional != $professional)
                            $updatedDetails .= "Professional was updated from " . $caldOldDetails->professional . " to " . $professional . ".<br>";

                        //add location for the job
                        $updatedDetails .= "Service Date: " . $jobDetails->service_date . ".<br>";
                        $updatedDetails .= "Start time: " . $jobDetails->service_time_start . ".<br>";
                        $updatedDetails .= "Start end: " . $jobDetails->service_time_end . ".<br>";
                        $updatedDetails .= "Location: " . $jobDetails->location_name . ".<br>";

                        if ($intID != '') {
                            //add to action items
                            actionItems::add($refNum, '', $intID, $clientID, "Updated Cald details. $updatedDetails", 'follow up');
                        }

                        if ($updatedDetails != '') {
                            //add to comments
                            $updatedDetails = str_replace("<br>", " ", $updatedDetails);
                            $commentIfo = "Updated Cald details. $updatedDetails";
                            addComments::add($refNum, 208, 'API User', $commentIfo);
                        }


                        //echo $this->sql;
                        $obj->error = false;
                        $obj->data = calds::getCald($caldID);
                    }

                    //check if location for patient is multiple
                    $this->updateJobLocation($refNum);

                } else {
                    $obj->error = true;
                    $obj->message = "Invalid Cald Details";
                }
            }
        }
        return $response->withJson( $obj );

    }

    public function removeCald(Request $request, Response $response, $args)
    {
        //log api request
        $this->logAPIRequest('Delete Cald', $request);

        $data = $request->getAttribute('data');
        $clientID = $data->clientid;
        $caldID = $args['id'];
        $obj = (object)['error' => false, 'message' => ''];

        //get reference number
        $caldDetails = calds::getCaldRefNum($caldID);
        if (!isset($caldDetails->refNum)) {
            $obj->error = true;
            $obj->message = "Cald Not Found";
            $obj->found = 0;

        } else {
            $refNum = $caldDetails->refNum;

            if ($jobid = details::exists($refNum, false, $clientID)) {
                //get cald details
                $caldOldDetails = calds::getCald($caldID);

                calds::remove($refNum, $caldID);

                //add to history
                addHistory::add($refNum, 'API Deleted Cald', 208, 'API User', "Deleted Cald - caldID: $caldID", '');

                //get interpreter id of job
                $intID = $this->getIntID($refNum);

                if ($intID != '') {

                    $updatedDetails = "Patient " . $caldOldDetails->name . " was deleted.<br>";

                    //add to action items
                    actionItems::add($refNum, '', $intID, $clientID, "Deleted Cald. $updatedDetails", 'follow up');
                }

                if ($updatedDetails != '') {
                    //add to comments
                    $updatedDetails = str_replace("<br>", " ", $updatedDetails);
                    $commentIfo = "Deleted Cald. $updatedDetails";
                    addComments::add($refNum, 208, 'API User', $commentIfo);
                }


                //echo $this->sql;
                $obj->error = false;

                //check if location for patient is multiple
                $this->updateJobLocation($refNum);

            } else {
                $obj->error = true;
                $obj->message = "Invalid Cald Details";
            }
        }
        return $response->withJson($obj);
    }

    private function logAPIRequest($process, $request, $extraParam = '') {
        //$requestParams = json_encode($_REQUEST);
        $requestParams = json_encode($request->getParams());
        $urlString = $request->getUri()->getPath();
        $urlString = ($extraParam != '') ? $urlString . "/$extraParam" : $urlString;

        //get what table to search for based on the invoice number
        $log = new standardSQLrequest( 'client_api_request' ) ;
        $log->setField('request_data', $requestParams);
        $log->setField('api_request', $process);
	$log->setField('url_string', $urlString);
        $log->insert();

    }

    private function getIntID($refNum) {
        //get interpreter id of job
        $this->where("Ref No", $refNum);
        $this->addFieldAlias('int_id', 'INT ID');
        $this->select();
        $intID = ($this->rows[0]->int_id == '') ? '' : $this->rows[0]->int_id;

        return $intID;

    }

    private function getJobDetails($refNum) {

        //get interpreter id of job
        $this->where("Ref No", $refNum);
        $this->addFieldAlias('int_id', 'INT ID');
        $this->addFieldAlias('service_time_start', 'Service Time start');
        $this->addFieldAlias('service_time_end', 'Service Time end');
        $this->addFieldAlias('locationID', 'location id');
        $this->addField('location_name');
        $this->addField('location_address_1');
        $this->addField('location_address_2');
        $this->addField( 'DATE_FORMAT(`Service Date`, "%W %D of %M %Y")' , 'jobs' , 'service_date' );
        $this->select();
        //echo $this->sql;
        return $this->rows[0];

    }

    private function updateJobLocation($refNum) {
        $countPatientLocations = calds::checkMultipleLocations($refNum);
        if ($countPatientLocations > 1) {
            $job = new standardSQLrequest( 'jobs' ) ;
            $job->setField('location_address_1', '');
            $job->where( 'Ref No' , $refNum);
            $job->update();
        }

    }


}
