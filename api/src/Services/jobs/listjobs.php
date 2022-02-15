<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 3:37 PM
 */

namespace API\jobs;

use API\sql\request as standardSqlRequest;
use API\traits\objectlist;
use API\jobs\listjobsObj;
use Slim\Http\Response;
use Slim\Http\Request;


class listjobs extends standardSqlRequest
{

    use objectlist;

    function __construct ()
    {
        parent::__construct('jobs');
    }

    private function listStandard (Request $request)
    {
        $data = $request->getAttribute('data');
        $this->where('INT ID', $data->fmid);
        $this->addFieldAlias('service_date', 'Service Date');
        $this->addField('DATE_FORMAT(`Service Date`, "%a %D of %M %Y")', 'jobs', 'service_date_formatted');
        $this->addField('DATE_FORMAT(`Service Date`, "%a %e %M %Y")', 'jobs', 'service_date_safari');
        $this->addFieldAlias('job_type', 'Inter trans');
        $this->addFieldAlias('client_name', 'Invoice To');
        $this->addFieldAlias('ref_num', 'Ref No');
        $this->addFieldAlias('language', 'Language');
        $this->addFieldAlias('fm_interpreter_id', 'INT ID');
        $this->addFieldAlias('booking_type', 'Inter trans');
        $this->addField('location_name');
        $this->addField('job_session_id');
        $this->addFieldCalc('location_address_1', 'CAST(CONVERT(jobs.location_address_1 USING utf8) AS binary)');
        $this->addFieldCalc('location_address_2', 'CAST(CONVERT(jobs.location_address_2 USING utf8) AS binary)');
        $this->addField('location_suburb');
        $this->addField('cancellation');
        $this->addField('not_serviced');
        $this->addField('location_postcode');
        $this->addFieldCalc('start', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%l:%i %p' )");
        $this->addFieldCalc('end', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%l:%i %p' )");
        $this->addFieldCalc('start_safari', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%T' )");
        $this->addFieldCalc('end_safari', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%T' )");
        $this->addLeftJoin('interpreters', 'INT ID', 'fm_interpreter_id');
    }

    public function slipsPending (Request $request, Response $response, $args)
    {
        $this->listStandard($request);
        $this->addSort('service_date', 'asc');
        $this->addSort('service_start', 'asc');
        $this->addLeftJoin("job_slips", "id", "job_id");
        $this->addLeftJoin("payment_lines", "Ref No", "ref_no");
        //$this->where( 'not_serviced' , '1', '!=', 'jobs');
        $this->where("`not_serviced` IS NULL [calc]", '[isBoolean]');
        $this->where('Cancellation', 'Yes', '!=', 'jobs');
        $this->where('Inter trans', 'Inter');
        $this->where('job_id', '[null]', ' is ', 'job_slips');
        $this->where('payment_line_id', '[null]', ' is ', 'payment_lines');
        //$this->where( '( not jobs.`Int slip received` or jobs.`Int slip received` is null )' , '[true]' );
        $this->between('Service Date', date('Y-m-d', strtotime('-6 month')), date('Y-m-d'));
//		$this->where( 'Service Date' , date( 'Y-m-d' , strtotime( '-6 month' ) ) , ' >= ' );
        //$this->select();
        //return $response->withJson((object)["error" => $this->sql]);
        $this->fetchObj = listjobsObj::class;
        return $this->getList($response);
    }

    public function slipsUpdated (Request $request, Response $response, $args)
    {
        $this->listStandard($request);
        $this->addSort('service_date', 'desc');
        $this->addSort('service_start', 'desc');
        $this->addJoin("job_slips", "id", "job_id");
        $this->addLeftJoin("payment_lines", "Ref No", "ref_no");
        $this->where("`not_serviced` IS NULL [calc]", '[isBoolean]');
        $this->where('Cancellation', 'Yes', '!=', 'jobs');
        $this->where('Inter trans', 'Inter');
        $this->where('payment_line_id', '[null]', ' is ', 'payment_lines');
        return $this->getList($response);
    }

    public function confirmed (Request $request, Response $response, $args)
    {
        $startDate = $request->getQueryParam('startDate');
        $endDate = $request->getQueryParam('endDate');

        $this->getConfirmed($request, $startDate, $endDate);
        $response = $response->withJson($this->rows);
        return $response;
    }

    public function getConfirmed (Request $request, $startDate = '', $endDate = '')
    {
        $this->listStandard($request);
        $this->addField('requested by', 'jobs', 'requested_by');
        $this->addField('Minimum Booking Length', 'clients', 'minLength');
        $this->addLeftJoin('clients', 'Client ID', 'Client ID');

        //get calds
        $subq = $this->retSubQuery('calds', 'nesbs');
        $subq->where('Ref Num', 'jobs.`Ref No`[calc]');
        $countCald = "count(nesbs.Name)";
        $fld = $subq->addFieldAlias('calds');
        $fld->setCalc($countCald);

        //get interpreter activities
        $intAct = $this->retSubQuery('activity_type_id', 'activities');
        $intAct->where('job_id', 'jobs.`Ref No`[calc]');
        $intAct->addSort('created', 'DESC');
        $intAct->recordLimit = 1;
        $intAct->addField('activity_type_id', 'activities');
        $this->where('Service Time start', '[notnull]');

        if ($startDate != '' && $endDate != '') {
            //add filter for date inclusion
            if ($startDate == $endDate) {
                $this->where(" `Service Date` =  '$startDate' [calc]", '[isBoolean]');
            }
            else {
                $this->between('Service Date', $startDate, $endDate, 'jobs');
            }
        }
        else {
            $this->where('Service Date', date('Y-m-d'), ' >= ');
        }
        $this->fetchObj = listjobsObj::class;
        $this->addSort('Service Date', 'asc', 'jobs');
        $this->addSort('Service Time start', 'asc', 'jobs');
        $this->select();

        return $this->rows;
    }

    public function getSessionDate (Request $request, Response $response, $args)
    {

        $jobID = $request->getQueryParam('jobID');
        $this->where('job_id', $jobID);

        $this->addField('activity_id', 'activities');
        $this->addField('DATE_FORMAT(time_start, "%l:%i %p")', 'activities', 'time_start');
        $this->addField('DATE_FORMAT(time_end, "%l:%i %p")', 'activities', 'time_end');
        $this->addField('actual_time_start', 'activities');
        $this->addField('actual_time_end', 'activities');
        $this->addField('title', 'activity_types');
        $this->addField('description', 'activity_types');
        $this->addField('abbreviation', 'activity_types');
        $this->addJoin('activity_types', 'activity_type_id');
        $this->addOrderBy('time_start', 'ASC', 'activities');
        //$this->addOrderBy( 'activity_id', 'DESC' ) ;
        $this->select();

        $obj = (object)['error' => ($this->error) ? true : false];

        $obj = (object)['error' => ($this->error) ? true : false];


        if ($this->found) $obj->data = $this->rows;

        $this->listStandard($request);
        $this->where('Service Date', date('Y-m-d'), ' >= ');
        $this->where('Service Time start', '[notnull]');
        $this->addSort('Service Date', 'asc', 'jobs');
        $this->addSort('Service Time start', 'asc', 'jobs');

        return $this->getList($response);

    }

    public static function getTimeEnd ($jobID)
    {
        $s = new self();
        $s->where('Ref No', $jobID);
        $s->addField('Service Time end', 'jobs', 'time_end');
        $s->select();
        return $s->rows[ 0 ]->time_end;
    }

    public function todaysSessions (Request $request, Response $response, $args)
    {
        $data = $request->getAttribute('data');
        $this->where('INT ID', $data->fmid);
        $this->addFieldAlias('service_date', 'Service Date');
        $this->addField('DATE_FORMAT(`Service Date`, "%a %D of %M %Y")', 'jobs', 'service_date_formatted');
        $this->addFieldAlias('job_type', 'Inter trans');
        $this->addFieldAlias('client_name', 'Invoice To');
        $this->addFieldAlias('ref_num', 'Ref No');
        $this->addFieldAlias('language', 'Language');
        $this->addField('location_name');
        $this->addField('location_address_1');
        $this->addField('location_address_2');
        $this->addField('location_suburb');
        $this->addField('location_postcode');
        $this->addFieldCalc('start', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_start ), '%l:%i %p' )");
        $this->addFieldCalc('end', "DATE_FORMAT(UTC_to_TS( state_from_postcode( interpreters.postcode), jobs.service_end ), '%l:%i %p' )");
        $this->addLeftJoin('interpreters', 'INT ID', 'fm_interpreter_id');

        $this->where('Inter Trans', 'Inter');
        $this->where("`not_serviced` IS NULL [calc]", '[isBoolean]');
        $this->where('Cancellation', 'Yes', '!=', 'jobs');
        $this->where('Service Date', date('Y-m-d'), ' = ');
        $this->where('Service Time start', '[notnull]');
        $this->where(" (`job_session_id` IS NULL OR `job_session_id` = 0) [calc]", '[isBoolean]');
        $this->addOrderBy('Service Time Start');

        //$getStartTime = $this->addLeftJoin('activities', 'ref no', 'job_id', 'jobs');
        //$getStartTime->alias = 'getStartTime';

        //$getEndTime = $this->addLeftJoin('activities', 'ref no', 'job_id', 'jobs');
        //$getEndTime->alias = 'getEndTime';

        //$this->addField('time_start', 'getStartTime', 'start_time');
        //$this->addField('time_start', 'getEndTime', 'end_time');

        //get time_start
        $subq = $this->retSubQuery('time_start', 'activities');
        $subq->where('job_id', 'jobs.`Ref No`[calc]');
        $subq->where('activity_type_id', '9');
        $subq->limit = 1;
        $subq->addFieldCalc('time_start', 'TIME_FORMAT(time_start, "%h:%i %p")');

        //get time_end
        $subq = $this->retSubQuery('time_end', 'activities');
        $subq->where('job_id', 'jobs.`Ref No`[calc]');
        $subq->where('activity_type_id', '10');
        $subq->limit = 1;
        $subq->addFieldCalc('time_end', 'TIME_FORMAT(time_end, "%h:%i %p")');


        return $this->getList($response);

    }


}
