<?php
/**
 * Created by PhpStorm.
 * User: satish
 * Date: 21/5/19
 * Time: 2:37 PM
 */

namespace API\jobs;

use ForceUTF8\Encoding;

class listjobsObj
{
    function __construct ()
    {
//        return print_r($this, true);
//        exit;
        if ($this->{'job_type'}) {
            if ($this->{'job_type'} == 'Inter') {
                $minLength = $this->{'minLength'};
                $start = $this->{'start'};
                $end = $this->{'end'};

                $start = date("H:i", strtotime($start));
                $end = date("H:i", strtotime($end));
                if ($minLength) {
                    $end = $this->getBookingLength($start, $end, $minLength);
                    $end = date("g:i A", strtotime($end));
                    $this->{'end'} = $end;
                }
            }
        }

        if ($this->requested_by) $this->requested_by = Encoding::toUTF8($this->requested_by);
        if ($this->location_suburb) $this->location_suburb = Encoding::toUTF8($this->location_suburb);

        if ($this->calds) {
            $this->calds = json_decode($this->calds);
        }
    }
    /**/
    /**
     * Provides a booking length.
     * If the job booking length is smaller than the client minimum booking length, select client minimum booking length.
     * If the job booking length is higher than the client minimum booking length, select the job job booking length.
     */
    function getBookingLength ($jobStartTime, $jobEndTime, $clientMinimumBookingLength)
    {

        $startTimeSecond = strtotime($jobStartTime);

        $endTimeSecond = strtotime($jobEndTime);


        /**Absolute value of time difference in seconds*/
        $jobDurationInSecond = abs($startTimeSecond - $endTimeSecond);

        if ($clientMinimumBookingLength) {
            /**Convert the minimum booking length into second. For Example: 1.5 hours would be 5400 seconds.*/
            $clientMinimumBookingLengthInSecond = ($clientMinimumBookingLength * 3600);

            if ($clientMinimumBookingLengthInSecond > $jobDurationInSecond) {
                // start by converting to seconds
                $seconds = ($clientMinimumBookingLength * 3600);
                // we're given hours, so let's get those the easy way
                $hours = floor($clientMinimumBookingLength);
                // since we've "calculated" hours, let's remove them from the seconds variable
                $seconds -= $hours * 3600;
                // calculate minutes left
                $minutes = floor($seconds / 60);
                // remove those from seconds as well
                $seconds -= $minutes * 60;
                // return the time formatted HH:MM:SS
                $time2 = $this->lz($hours) . ":" . $this->lz($minutes) . ":" . $this->lz($seconds);
                $secs = strtotime($time2) - strtotime("00:00:00");
                $result = date("H:i:s", strtotime($jobStartTime) + $secs);
                return $result;
            }
        }
        return $jobEndTime;
    }

    function lz ($num)
    {
        return (strlen($num) < 2) ? "{$num}" : $num;
    }

}