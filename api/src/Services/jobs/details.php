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
use API\interpreter\sessionalClients as sessionalClients;
use Slim\Http\Response;
use Slim\Http\Request;
use API\jwt\token as token;
use API\utility\appLogging as appLogging;
use API\jobs\detailsObj;
use ForceUTF8\Encoding;

class details extends standardSqlRequest
{

    function __construct ($container = '')
    {
        parent::__construct('jobs');
        $this->fetchObjParams = [$container];
        $this->fetchObj = jobobj::class;
    }

    public function joinClient ($type = 'left')
    {
        if (!@ $this->{'joinClientAdded'}) {
            $this->Join('to:clients', 'pkey:client_id', 'skey:id', "type:$type");
            $this->{'joinClientAdded'} = true;
        }
    }

    public function joinLocation ($type = 'left')
    {
        if (!@ $this->{'joinLocationAdded'}) {
            $this->Join('to:clients', 'pkey:Location ID', 'skey:Client ID', "type:$type", 'alias:location');
            $this->{'joinLocationAdded'} = true;
        }
    }


    /**
     * @param $ref
     * @return mixed
     */
    public static function getId ($ref)
    {
        $s = new self();
        $s->where('Ref No', $ref);
        $s->addField('id');
        $s->select();
        return $s->rows[ 0 ]->id;
    }

    public static function exists ($ref, $intid = false, $clientID = false)
    {
//		print_r( $ref. '   ' . $intid ); exit;
        $s = new self();
        $s->where('Ref No', $ref);

        if ($intid) $s->where('INT ID', $intid);

        if ($clientID) {
            $clientIDS = self::getClientIDS($clientID);
            $s->in('Client ID', $clientIDS);
        }

        $s->addField('id');
        $s->select();
//        echo $s->sql; exit;
        return ($s->found) ? $s->rows[ 0 ]->id : false;


    }

    private static function getClientIDS ($clientID)
    {
        //get parent ID of client ID
        $clientIDS = [];
        //select b.parent_id_for_reporting from clients as a, client_preferences as b where a.`client id` = 'DHS9974' AND a.id = b.client_id
        $parentClient = new standardSQLrequest('clients');
        $parentClient->addField('parent_id_for_reporting', 'client_preferences');
        $parentClient->addJoin("client_preferences", "id", "client_id", 'clients');
        $parentClient->where('Client ID', $clientID, '=', 'clients');
        $parentClient->select();

        //check if the client id is the parent client id
        $checkClient = new standardSQLrequest('client_preferences');
        $checkClient->where('parent_id_for_reporting', $clientID);
        $checkClient->addCount();
        $checkClient->select();
        $clientCount = $checkClient->rows[ 0 ]->num;

        if (count($parentClient->rows) < 1 && $clientCount < 1) {
            $clientIDS[] = $clientID;
        }
        else {
            $parentClientID = ($clientCount > 0) ? $clientID : $parentClient->rows[ 0 ]->parent_id_for_reporting;
            $clients = new standardSQLrequest('client_preferences');
            $clients->addField('Client ID', "clients", "clientID");
            $clients->addJoin("clients", "client_id", "id", 'client_preferences');
            $clients->where('parent_id_for_reporting', $parentClientID, '=', 'client_preferences');
            $clients->select();
            $records = $clients->rows;
            foreach ($records as $record)
                $clientIDS[] = $record->clientID;

        }
        return $clientIDS;
    }


    public static function set ($jobid, $field, $val)
    {

    }

    /**
     * Returns the current job for a sessional interpreter.
     *
     * Uses data fmid and state that is stored in the token to find a job whose start time is within half an hour of the current time.
     * service_start is a datetime field stored in UTC. So conversion is done to convert server local time to utc as part of the search.
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function sessionalJob (Request $request, Response $response, $args)
    {
        $sess = new self;
        //$sess->standardFields() ;
        $sess->standardItemFields();
        $data = $request->getAttribute('data');
        $sess->where('INT ID', $data->fmid);
        $sess->where('Inter trans', 'Inter');
        $sess->where(" `Service Date` =  CURDATE() [calc]", '[isBoolean]');
        $sess->where(" `job_session_id` IS NOT NULL [calc]", '[isBoolean]');
        $sess->select();
        $sess->joins = '';


        $obj = (object)['error' => ($this->error) ? true : false];
        if ($sess->found == 1) {
            $obj->data = $sess->rows[ 0 ];
        }
        elseif ($sess->found > 1) {
            $sess->standardItemFields();
            $sess->where('INT ID', $data->fmid);
            $sess->where('Inter trans', 'Inter');
            //$from = "TS_to_UTC( '" . $data->state . "' , now() - INTERVAL 30 MINUTE )[calc]";
            //$to = "TS_to_UTC( '" . $data->state . "' , now() + INTERVAL 30 MINUTE )";
            $from = "TS_to_UTC( '" . $data->state . "' , now())";
            $to = "TS_to_UTC( '" . $data->state . "' , now())";

            //$sess->between( 'service_start' , $from , $to );

            $sess->where("service_start <= $from [calc]", '[isBoolean]');
            $sess->where("service_end >= $to [calc]", '[isBoolean]');

            $sess->select();
            //echo $sess->sql;
            if ($sess->found > 1) {
                $obj->error = true;
                $obj->errorMessage = 'Multiple Jobs found';
            }
            else {
                if ($sess->found == 0) {
                    $obj->error = true;
                    $obj->errorMessage = 'No records found.';
                }
                else {
                    $obj->data = $sess->rows[ 0 ];
                }
            }

            $sess->joins = '';
        }
        else {
            $obj->error = true;
            $obj->errorMessage = 'No records found.';
        }


        $response = $response->withJson($obj);

        return $response;
    }

    public function confirmedSessions (Request $request, Response $response, $args)
    {
        //$clients = sessionalClients::getClients();

        $sess = new self;
        //$sess->standardFields() ;
        $data = $request->getAttribute('data');
        //issue with fields having spaces in between their name, causing issue with javascript/backbone code
        //used aliasing on fields that has spaces in between
        $sess->standardItemFields();

        $sess->where('INT ID', $data->fmid);
        $sess->where('Inter trans', 'Inter');
        //$sess->where( " `service date` >=  CURDATE() + INTERVAL 1 DAY [calc]" , '[isBoolean]' ) ;
        $sess->where(" `service date` >=  CURDATE() [calc]", '[isBoolean]');
        $sess->where(" `job_session_id` IS NOT NULL [calc]", '[isBoolean]');
        $sess->addSort('service date', 'ASC');
        $sess->select();

        //echo $sess->sql;
        $obj = (object)['error' => ($this->error) ? true : false];

        if ($sess->found > 0) {
            $obj->data = $sess->rows;
        }
        else {
            $obj->error = true;
            $obj->errorMessage = 'No records found.';
        }

        $response = $response->withJson($obj);
        return $response;
    }


    private function standardItemFields ()
    {
        $this->addField('id');
        $this->addField('job_session_id');
        $this->addField('location_name');
        $this->addField('location_address_1');
        $this->addField('location_address_2');
        $this->addField('location_suburb');
        $this->addField('language', 'jobs');
        $this->addField('location_postcode');
        $this->addField('Ref No', '', 'ref');
        $this->addField('DATE_FORMAT(`Service Date`, "%W %D of %M %Y")', '', 'service_date');
        $this->addField('Client ID', '', 'client_id');
        $this->addLeftJoin('interpreters', 'INT ID', 'fm_interpreter_id');

        $this->addFieldCalc('interpreter_start', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%l:%i %p' )");
        $this->addFieldCalc('interpreter_end', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%l:%i %p' )");

    }

    private function locationDetails ()
    {
        $this->joinLocation();
        $this->addField('Location ID');
        $this->addField('location_name');
        $this->addFieldCalc('location_address_1', 'CAST(CONVERT(jobs.location_address_1 USING utf8) AS binary)');//
        $this->addFieldCalc('location_address_2', 'CAST(CONVERT(jobs.location_address_2 USING utf8) AS binary)');
        $this->addField('location_suburb');
        $this->addField('location_postcode');
        $this->addField('home_visit');
        $this->addFieldCalc('location_details', "if( empty( jobs.location_details ) , location.`Location Details` , jobs.location_details )");
    }

    /**
     * Set the job fields that will be most commonly needed by calls to the API.
     *
     */
    private function standardFields ()
    {

        $this->addField('Ref No', 'jobs', 'ref_num');
        //$this->addField( 'Service Date', 'jobs', 'service_date' );
        $this->addField('DATE_FORMAT(`Service Date`, "%a %D of %M %Y")', 'jobs', 'service_date');
        $this->addField('Invoice To', 'jobs', 'invoice_to');
        $this->addFieldCalc('start', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%l:%i %p' )");
        $this->addFieldCalc('end', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%l:%i %p' )");

        $this->addField('DATE_FORMAT(`Service Date`, "%a %e %M %Y")', 'jobs', 'service_date_safari');
        $this->addFieldCalc('start_safari', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%T' )");
        $this->addFieldCalc('end_safari', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%T' )");


        // Temp
        $this->addField('id');
        $this->addField('Ref No');
        $this->addFieldAlias('service_date', 'Service Date');

        $this->addField('Service Time start');
        $this->addField('Service Time end');
        $this->addFieldAlias('job_type', 'Inter trans');
        $this->addField('INT ID');
        $this->addField('Client ID');
        $this->addField('Invoice To');
        $this->addField('Int slip received');
        $this->addField('Int slip received date');
        $this->locationDetails();
//        $this->addField('Location ID');
//        $this->addField('location_name');
//        $this->addFieldCalc('location_address_1', 'CAST(CONVERT(location_address_1 USING utf8) AS binary)');//
//        $this->addFieldCalc('location_address_2', 'CAST(CONVERT(location_address_2 USING utf8) AS binary)');
//        $this->addField('location_suburb');
//        $this->addField('location_postcode');
//        $this->addField('home_visit');
        $this->addField('Other Info');
        $this->addField('Professional');
        $this->addField('Requested by');
        $this->addField('Cancellation');
        $this->addLeftJoin('interpreters', 'INT ID', 'fm_interpreter_id');
        $this->addFieldCalc('interpreter_start', "UTC_to_TS( state_from_postcode( interpreters.postcode) , jobs.service_start )");
        $this->addFieldCalc('interpreter_end', "UTC_to_TS( state_from_postcode( interpreters.postcode) , jobs.service_end )");
        $this->addFieldAlias('language', 'Language');
        $this->addFieldAlias('phone', 'Clients Phone No');
        $this->addFieldAlias('email', 'Email');
        $this->addFieldAlias('slip_received', 'Int slip received');
        $subq = $this->retSubQuery('calds', 'nesbs');
        $subq->where('Ref Num', 'jobs.`Ref No`[calc]');
        $leftBrace = "'{'";
        $rightBrace = "'}'";
        $nameLabel = "'\"name\":\"', TRIM(nesbs.Name) , '\"'";
        $refLabel = "',\"reference\":\"' , TRIM(nesbs.Reference) , '\"'";
        $timeLabel = "',\"start_time\":\"' , TRIM(ifnull( DATE_FORMAT(nesbs.start_time, '%l:%i %p')) , '' ) , '\"'";
        $locLabel = "',\"location\":\"' , TRIM(ifnull( nesbs.location , '' )) , '\"'";
        $singleCald = "concat( $leftBrace , $nameLabel , $refLabel , $timeLabel, $locLabel , $rightBrace )";
        $group = "GROUP_CONCAT( $singleCald ORDER BY start_time  )";
        $putItAllTogether = "concat( '" . '[' . "' , $group , '" . ']' . "' )";
        $fld = $subq->addFieldAlias('calds');
        $fld->setCalc($putItAllTogether);

        $this->leftJoin('jobs_extend', 'Ref No', 'ref_num');
        $this->addField('reporting_client_parent_id', 'jobs_extend');


    }


    /**
     * Returns the job by the Ref No.
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function get (Request $request, Response $response, $args)
    {

        //get token data
        $tokenData = $request->getAttribute('data');
        $clientID = $tokenData->clientid;
        $intID = $tokenData->fmid;

        $this->fetchObj = detailsObj::class;

        $this->where('Ref No', $args[ 'id' ]);
        $this->standardFields();

        $this->addLeftJoin('clients', 'Client ID', 'Client ID');
        $this->addField('Minimum Booking Length', 'clients', 'minLength');

        $this->addField('requested by', 'jobs', 'requested_by');
        $this->addField('cancellation', 'jobs');
        $this->addField('created', 'job_slips', 'slip_received_date');
        $this->addFieldCalc('slip_id', "IFNULL(job_slips.id, '0')");
        $this->addFieldCalc('actual_start', "IFNULL(UTC_to_TS( state_from_postcode( interpreters.postcode) , jobs.actual_start ), UTC_to_TS( state_from_postcode( interpreters.postcode) , jobs.service_start ) )");
        $this->addFieldCalc('actual_end', "IFNULL(UTC_to_TS( state_from_postcode( interpreters.postcode) , jobs.actual_end ), UTC_to_TS( state_from_postcode( interpreters.postcode) , jobs.service_end ))");

        //restrict the user/contact to only view his/her jobs
        if ($clientID != '') {
            $this->where('Client ID', $clientID);
        }
        else {
            $this->where('INT ID', $intID);
        }

        //get interpreter activities
        $intAct = $this->retSubQuery('activity_type_id', 'activities');
        $intAct->where('job_id', 'jobs.`Ref No`[calc]');
        $intAct->addSort('created', 'DESC');
        $intAct->recordLimit = 1;
        $intAct->addField('activity_type_id', 'activities');

        $this->addLeftJoin('job_slips', 'id', 'job_id');
        //$history = $this->addLeftJoin('history', 'ref no', 'ref num');
        //$history->where( " `description` LIKE 'Cancelled Booking%' [calc]" , '[isBoolean]' );

        //original interpreter
        $subq = $this->retSubQuery('cancellationReason', 'history');
        $subq->addField('description');
        $subq->where('refnum', 'jobs.`ref no` [calc]');
        $subq->where(" `description` LIKE 'Cancelled Booking%' [calc]", '[isBoolean]');
        $subq->recordLimit = 1;

        $subq = $this->retSubQuery('calds', 'nesbs');
        $subq->where('Ref Num', 'jobs.`Ref No`[calc]');
        //$subq->addOrderBy( 'start_time');
        $leftBrace = "'{'";
        $rightBrace = "'}'";
        $nameLabel = "'\"name\":\"', nesbs.Name , '\"'";
        $refNumLabel = "',\"refNum\":\"' , nesbs.`Ref Num` , '\"'";
        $modifiedLabel = "',\"bookingUpdated\":\"' , nesbs.modified , '\"'";
        $refLabel = "',\"reference\":\"' , nesbs.Reference , '\"'";
        $timeLabel = "',\"start_time\":\"' , ifnull( DATE_FORMAT(nesbs.start_time, '%l:%i %p') , '' ) , '\"'";
        $locLabel = "',\"location\":\"' , ifnull( nesbs.location , '' ) , '\"'";
        $singleCald = "concat( $leftBrace , $nameLabel , $refLabel , $refNumLabel, $modifiedLabel, $timeLabel, $locLabel , $rightBrace )";
        $group = "GROUP_CONCAT( $singleCald ORDER BY start_time  )";
        $putItAllTogether = "concat( '" . '[' . "' , $group , '" . ']' . "' )";
        $fld = $subq->addFieldAlias('calds');
        $fld->setCalc($putItAllTogether);


        $this->select();

//        echo '<pre>' . print_r( $this->rows , true ) . '</pre>'; exit;

//        echo '<pre>' . print_r( $this , true ) . '</pre>'; exit;

        $obj = (object)['error' => ($this->error) ? true : false];
        if ($this->found) {
            $obj->data = $this->rows[ 0 ];
//            if ($obj->data->templatePath) {
//
//            }
            $obj->data->{'Other Info'} = Encoding::toUTF8($obj->data->{'Other Info'});
            $obj->data->location_details = Encoding::toUTF8($obj->data->location_details);
            $obj->data->location_name = Encoding::toUTF8($obj->data->location_name);
            $obj->data->location_suburb = Encoding::toUTF8($obj->data->location_suburb);
            $obj->data->Professional = Encoding::toUTF8($obj->data->Professional);
            $obj->data->phone = Encoding::toUTF8($obj->data->phone);
        }
        $response = $response->withJson($obj);

        return $response;
    }


    public function getClientDetails ($clientID)
    {
        $this->addLeftJoin('interpreters', 'INT ID', 'fm_interpreter_id');
    }

    public function getSessionDate (Request $request, Response $response, $args)
    {
        $selectedDate = $request->getQueryParam('selectedDate');
        $selectedDate = date("Y-m-d", strtotime($selectedDate));
        $roleID = $request->getQueryParam('roleID');
        $clients = sessionalClients::getClients($roleID);

        //get data for the selected state
        $this->addFieldCalc('service_date', "DATE_FORMAT( `Service Date`, '%d-%m-%Y' )");
        $this->addFieldCalc('service_date_formatted', "DATE_FORMAT( `Service Date`, '%d-%b-%Y' )");
        $this->addField('Ref No', '', 'job_id');
        $this->addField('INT ID', 'jobs', 'int_id');
        $this->addField('job_session_id', 'jobs');
        $this->addField('naati_number', 'interpreters');
        $this->addField('language', 'jobs');
        $this->addFieldCalc('interpreter', "CONCAT(interpreters.first_name, ' ', interpreters.surname)");
        $this->addFieldCalc('service_time_start', "DATE_FORMAT(UTC_to_TS(  clients.state, `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('service_time_end', "DATE_FORMAT(UTC_to_TS( clients.state, `service_end` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_start', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_end', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_end` ), '%l:%i %p' )");

        $this->addField('location_name', '', 'location');
        $this->addField('state', 'clients');
        $this->addField('mobile', 'interpreters');
        $this->where('Service Date', $selectedDate);
        $this->where(" `job_session_id` IS NOT NULL [calc]", '[isBoolean]');

        $this->addJoin('clients', 'Client ID');
        //$this->in('Client ID', $clients, 'clients');
        $clients = join("','", $clients);
        $clients = "'" . $clients . "'";
        $this->where("( `Location ID` IN (" . $clients . ") OR jobs.`Client ID` IN (" . $clients . ") )", '[isBoolean]');


        $this->addJoin('interpreters', 'INT ID', 'fm_interpreter_id');
        $this->select();


        appLogging::createLog($request, "View by date");


        $this->joins = null; //clear joins
        //echo $this->sql;
        $obj = (object)['error' => ($this->error) ? true : false];
        $obj->data = $this->rows;

        $response = $response->withJson($obj);
        return $response;
    }

    public function logoutLog (Request $request, Response $response, $args)
    {
        appLogging::createLog($request, "Logout");
    }

    public function getNoInterpreters (Request $request, Response $response, $args)
    {
        $selectedDate = $request->getQueryParam('selectedDate');
        $selectedDate = date("Y-m-d", strtotime($selectedDate));
        $roleID = $request->getQueryParam('roleID');


        //get data for the selected state
        $this->addFieldCalc('service_date', "DATE_FORMAT( `Service Date`, '%d-%m-%Y' )");
        $this->addFieldCalc('service_date_formatted', "DATE_FORMAT( `Service Date`, '%d-%b-%Y' )");
        $this->addField('Ref No', '', 'job_id');
        $this->addField('INT ID', 'jobs', 'int_id');
        $this->addField('job_session_id', 'jobs');
        $this->addField('naati_number', 'interpreters');
        $this->addField('language', 'jobs');
        $this->addFieldCalc('interpreter', "CONCAT(interpreters.first_name, ' ', interpreters.surname)");
        $this->addFieldCalc('service_time_start', "DATE_FORMAT(UTC_to_TS(  clients.state, `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('service_time_end', "DATE_FORMAT(UTC_to_TS( clients.state, `service_end` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_start', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_end', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_end` ), '%l:%i %p' )");

        $this->addField('location_name', '', 'location');
        $this->addField('state', 'clients');
        $this->addField('mobile', 'interpreters');
        $this->where('INT ID', '');

        $this->addJoin('clients', 'client_id', "id");
        $this->where(" `job_session_id` IS NOT NULL [calc]", '[isBoolean]');

        if ($roleID != 0) {
            $clients = sessionalClients::getClients($roleID);
            $clients = join("','", $clients);
            $clients = "'" . $clients . "'";
            $this->where("( `Location ID` IN (" . $clients . ") OR jobs.`Client ID` IN (" . $clients . ") )", '[isBoolean]');
            //$this->in('Client ID', $clients, 'clients');
        }

        $this->addLeftJoin('interpreters', 'interpreter_id', 'interpreter_id');
        //$this->addOrderBy('service_date', 'ASC', 'jobs');
        $this->select();
        //echo $this->sql;

        $this->joins = null; //clear joins
        //echo $this->sql;
        $obj = (object)['error' => ($this->error) ? true : false];
        $obj->data = $this->rows;

        $response = $response->withJson($obj);
        return $response;
    }

    public function getUnfilled (Request $request, Response $response, $args)
    {
        $roleID = $request->getQueryParam('roleID');
        $startDate = $request->getQueryParam('startDate');
        $endDate = $request->getQueryParam('endDate');
        $startDate = date("Y-m-d", strtotime($startDate));
        $endDate = date("Y-m-d", strtotime($endDate));

        //get data for the selected state
        $this->addFieldCalc('service_date', "DATE_FORMAT( `Service Date`, '%d-%m-%Y' )");
        $this->addFieldCalc('service_date_formatted', "DATE_FORMAT( `Service Date`, '%d-%b-%Y' )");
        $this->addField('Ref No', '', 'job_id');
        $this->addField('INT ID', 'jobs', 'int_id');
        $this->addField('job_session_id', 'jobs');
        $this->addField('naati_number', 'interpreters');
        $this->addField('language', 'jobs');
        $this->addFieldCalc('interpreter', "CONCAT(interpreters.first_name, ' ', interpreters.surname)");
        $this->addFieldCalc('service_time_start', "DATE_FORMAT(UTC_to_TS(  clients.state, `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('service_time_end', "DATE_FORMAT(UTC_to_TS( clients.state, `service_end` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_start', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_end', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_end` ), '%l:%i %p' )");

        $this->addField('location_name', '', 'location');
        $this->addField('state', 'clients');
        $this->addField('mobile', 'interpreters');
        $this->where('INT ID', '');

        $this->addJoin('clients', 'Client ID');
        $this->where(" `job_session_id` IS NOT NULL [calc]", '[isBoolean]');

        if ($roleID != 0) {
            $clients = sessionalClients::getClients($roleID);
            $clients = join("','", $clients);
            $clients = "'" . $clients . "'";
            $this->where("( `Location ID` IN (" . $clients . ") OR jobs.`Client ID` IN (" . $clients . ") )", '[isBoolean]');
            //$this->in('Client ID', $clients, 'clients');
        }

        //add filter for date inclusion
        if ($startDate == $endDate) {
            $this->where(" `Service Date` =  '$startDate' [calc]", '[isBoolean]');
        }
        else {
            $this->between('Service Date', $startDate, $endDate, 'jobs');
        }

        $this->addJoin('interpreters', 'INT ID', 'fm_interpreter_id');
        $this->addOrderBy('Service Date'); //order by service date
        $this->select();

        appLogging::createLog($request, "Get Unfilled");
        //echo $this->sql;

        $this->joins = null; //clear joins
        //echo $this->sql;
        $obj = (object)['error' => ($this->error) ? true : false];
        $obj->data = $this->rows;

        $response = $response->withJson($obj);
        return $response;
    }

    public function getReliefRecords (Request $request, Response $response, $args)
    {
        $this->joins = null; //clear joins
        $roleID = $request->getQueryParam('roleID');
        $startDate = $request->getQueryParam('startDate');
        $endDate = $request->getQueryParam('endDate');
        $startDate = date("Y-m-d", strtotime($startDate));
        $endDate = date("Y-m-d", strtotime($endDate));


        //$this->addFieldCalc('relief', 'if (interpreters.`interpreter_id` = job_session_blocks.`interpreter_id`, 0, 1)');
        //get data for the selected state
        $this->addFieldCalc('service_date', "DATE_FORMAT( `Service Date`, '%d-%m-%Y' )");
        $this->addFieldCalc('service_date_formatted', "DATE_FORMAT( `Service Date`, '%d-%b-%Y' )");
        $this->addField('Ref No', '', 'job_id');
        $this->addField('INT ID', 'jobs', 'int_id');
        $this->addField('job_session_id', 'jobs');
        $this->addField('naati_number', 'interpreters');
        $this->addField('language', 'jobs');
        $this->addFieldCalc('interpreter', "CONCAT(interpreters.first_name, ' ', interpreters.surname)");
        $this->addFieldCalc('service_time_start', "DATE_FORMAT(UTC_to_TS(  clients.state, `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('service_time_end', "DATE_FORMAT(UTC_to_TS( clients.state, `service_end` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_start', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_start` ), '%l:%i %p' )");
        $this->addFieldCalc('vic_service_time_end', "DATE_FORMAT(UTC_to_TS( 'VIC', `service_end` ), '%l:%i %p' )");

        $this->addField('location_name', '', 'location');
        $this->addField('state', 'clients');
        $this->addField('mobile', 'interpreters');

        $this->addJoin("job_sessions", "job_session_id");
        $this->addJoin("job_session_blocks", "job_session_block_id", "job_session_block_id", "job_sessions");

        $this->where(" jobs.`job_session_id` IS NOT NULL [calc]", '[isBoolean]');
        $this->where(" jobs.`INT ID` != '' [calc]", '[isBoolean]');
        $this->where(" interpreters.`interpreter_id` != job_session_blocks.`interpreter_id` [calc]", '[isBoolean]');


        $this->addJoin('clients', 'Client ID');
        if ($roleID != 0) {
            $clients = sessionalClients::getClients($roleID);
            $clients = join("','", $clients);
            $clients = "'" . $clients . "'";
            $this->where("( `Location ID` IN (" . $clients . ") OR jobs.`Client ID` IN (" . $clients . ") )", '[isBoolean]');
            //$this->in('Client ID', $clients, 'clients');
        }

        //add filter for date inclusion
        if ($startDate == $endDate) {
            $this->where(" `Service Date` =  '$startDate' [calc]", '[isBoolean]');
        }
        else {
            $this->between('Service Date', $startDate, $endDate, 'jobs');
        }

        $this->addJoin('interpreters', 'INT ID', 'fm_interpreter_id');
        $this->addOrderBy('Service Date'); //order by service date
        $this->select();

        appLogging::createLog($request, "Get Relief");

        $this->joins = null; //clear joins
        //echo $this->sql;
        $obj = (object)['error' => ($this->error) ? true : false];
        $obj->data = $this->rows;

        $response = $response->withJson($obj);
        return $response;

    }

}
