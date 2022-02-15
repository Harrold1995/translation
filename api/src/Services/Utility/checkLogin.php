<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;


/**
 * @property  fm_interpreter_id
 */
class checkLogin
{

	public $int_id;
    public $job_id;
    public $is_clocked_on;
    public $on_break;
    public $break_consumption;
    public $is_clocked_off;
    

	function __construct()
	{
        $jobID = $this->job_id;
        $timeEnd = $this->service_end;
        $this->on_break = checkBreak::checkInterpreterBreak($jobID);
        $this->break_consumption = ($this->on_break == 2 || $this->on_break == 1) ? checkBreak::getBreakConsumption($jobID) : 0;
        $this->is_clocked_off = checkClockOff::checkClockOff($jobID);

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

	}
}