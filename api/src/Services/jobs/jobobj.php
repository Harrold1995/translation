<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 17/2/17
 * Time: 9:45 PM
 */

namespace API\jobs;

use API\functions\debug;
use API\functions\utility;
use API\utility\checkBreak as checkBreak ;
use API\utility\checkClockOff as checkClockOff ;
use API\utility\loginLogs as loginLogs ;
use API\utility\checkClockOn as checkClockOn ;
use API\utility\checkInterpreterReplacement as checkInterpreterReplacement ;
use ForceUTF8\Encoding;


class jobobj
{
    public $int_id;
    public $job_id;
    public $is_clocked_on;
    public $on_break;
    public $break_consumption;
    public $is_relief;
    public $is_clocked_off;
    public $html = false ;

	function __construct( $container = '' )
	{
		$this->calds = str_replace(array("\r", "\n"), array('', ''), $this->calds);
		$this->calds = json_decode( $this->calds ) ;

        $jobID = $this->job_id;
        $timeEnd = $this->service_end;
        $this->on_break = checkBreak::checkInterpreterBreak($jobID);
        $this->break_consumption = ($this->on_break == 2 || $this->on_break == 1) ? checkBreak::getBreakConsumption($jobID) : 0;
        $this->is_relief = checkInterpreterReplacement::check($jobID);
        $this->is_clocked_off = checkClockOff::checkClockOff($jobID);
        $this->current_time = checkClockOn::getCurrentTime($this->state);

        if ( $this->int_id ) {
            $interpreterID = $this->int_id;
            $isLoggedIn = (loginLogs::checkIfLoggedIn( $interpreterID, $jobID) == true) ? 1 : 0;
            $this->is_logged_in = $isLoggedIn;
            $this->early_clock_off = checkClockOff::checkEarlyClockOff($jobID, $timeEnd);

        }

        if ( $this->job_id ) {
            //check if the interpreter is clocked on
            $isClockedOn = (checkClockOn::checkIfClockedOn( $jobID ) == true) ? 1 : 0;
            $this->is_clocked_on = $isClockedOn;
        }

        if ( $this->service_time_start ) {
            $startTime = $this->service_time_start;
            $startTime = date("h:i A", strtotime($startTime));
            $this->service_time_start = $startTime;
        }

        if ( $this->service_time_end ) {
            $endTime = $this->service_time_end;
            $endTime = date("h:i A", strtotime($endTime));
            $this->service_time_end = $endTime;
        }

		if ( file_exists( '../templates/clients/jobdetail/mobile/' . $this->reporting_client_parent_id . '.phtml'  ) )
		{
			$this->templatePath = 'clients/jobdetail/mobile/' . $this->reporting_client_parent_id . '.phtml';
			$thisArray = (array)$this ;
			utility::fixArrayKey( $thisArray );
			$this->html = $container->renderer->fetch( $this->templatePath , $thisArray );
//			print_r( $this ); exit;
		}

//        $this->html = Encoding::toUTF8( $this->html );
        $this->html = '' ;
        $this->location_name = Encoding::toUTF8( $this->location_name );
        $this->location_address_1 = Encoding::toUTF8( $this->location_address_1 );
        $this->location_address_2 = Encoding::toUTF8( $this->location_address_2 );
        $this->location_details = str_replace( "\r" , "\n" , Encoding::toUTF8( $this->location_details ) );
        $this->{'Other Info'} = Encoding::toUTF8( $this->{'Other Info'} );

//		echo '<pre>' . print_r( $this , true ) . '</pre>'; exit;


//		if ( debug::in())
//		{
//			debug::log( $this );

//		}



	}
}