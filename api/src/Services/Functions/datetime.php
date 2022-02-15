<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 10/8/17
 * Time: 2:08 PM
 */

namespace API\functions;


class datetime
{
	public static function time2str( $ts )
	{
		if ( !ctype_digit( $ts ) )
			$ts = strtotime( $ts );
		$diff = time() - $ts;
		if ( $diff == 0 )
			return 'now';
		elseif ( $diff > 0 )
		{
			$day_diff = floor( $diff / 86400 );
			if ( $day_diff == 0 )
			{
				if ( $diff < 60 ) return 'just now';
				if ( $diff < 120 ) return '1 minute ago';
				if ( $diff < 3600 ) return floor( $diff / 60 ) . ' minutes ago';
				if ( $diff < 7200 ) return '1 hour ago';
				if ( $diff < 86400 ) return floor( $diff / 3600 ) . ' hours ago';
			}
			if ( $day_diff == 1 ) return 'Yesterday';
			if ( $day_diff < 7 ) return $day_diff . ' days ago';
			if ( $day_diff < 31 ) return ceil( $day_diff / 7 ) . ' weeks ago';
			if ( $day_diff < 60 ) return 'last month';
			return date( 'F Y', $ts );
		}
		else
		{
			$diff = abs( $diff );
			$day_diff = floor( $diff / 86400 );
			if ( $day_diff == 0 )
			{
				if ( $diff < 120 ) return 'in a minute';
				if ( $diff < 3600 ) return 'in ' . floor( $diff / 60 ) . ' minutes';
				if ( $diff < 7200 ) return 'in an hour';
				if ( $diff < 86400 ) return 'in ' . floor( $diff / 3600 ) . ' hours';
			}
			if ( $day_diff == 1 ) return 'Tomorrow';
			if ( $day_diff < 4 ) return date( 'l', $ts );
			if ( $day_diff < 7 + ( 7 - date( 'w' ) ) ) return 'next week';
			if ( ceil( $day_diff / 7 ) < 4 ) return 'in ' . ceil( $day_diff / 7 ) . ' weeks';
			if ( date( 'n', $ts ) == date( 'n' ) + 1 ) return 'next month';
			return date( 'F Y', $ts );
		}
	}

	private static function indexOfMatch( $arr, $val )
	{
		foreach ( $arr as $key => $item )
		{
			if ( $item[ 0 ] == $val ) return $key;
		}
		return false;
	}

	/**
	 * @param $dateStr string
	 * @param $format  string    Must contain a d, m and y eg d/m/y
	 *
	 * @return string
	 */
	public static function dateFromFormat( $dateStr, $format )
	{
		$pattern = '/(.*)[,:\s\\/-](.*)[,:\s\\/-](.*)/us';
		preg_match_all( $pattern, $dateStr, $dsArr );
		preg_match_all( $pattern, $format, $forArr );
		$year = $dsArr[ self::indexOfMatch( $forArr, 'y' ) ][ 0 ];
		if ( strlen( $year ) < 4 ) $year = '20' . $year;
		$month = str_pad( $dsArr[ self::indexOfMatch( $forArr, 'm' ) ][ 0 ], 2, '0', STR_PAD_LEFT );
		$day = str_pad( $dsArr[ self::indexOfMatch( $forArr, 'd' ) ][ 0 ], 2, 0, STR_PAD_LEFT );
		return $year . '-' . $month . '-' . $day;
	}

	public static function formatDate( $format, $date = false )
	{
		if ( $format == 'full' ) $format = 'l jS F Y';
		if ( $format == 'long' ) $format = 'jS F Y';
		return date( $format, ( $date ) ? strtotime( $date . ' 00:00:00' ) : time() );
	}

	public static function formatDateTime( $format, $datetime )
	{
		if ( $format == 'full' ) $format = 'l jS F Y g:i a';
		if ( $format == 'long' ) $format = 'jS F Y g:i a';
		return date( $format, strtotime( $datetime ) );
	}

	public static function timeToSeconds( $time )
	{
		return strtotime( "1970-01-01 $time UTC" );
	}

	public static function secondsToTime( $seconds, $format = '%h:%i:%s' )
	{
		$dtF = new \DateTime( '@0' );
		$dtT = new \DateTime( "@$seconds" );
		return $dtF->diff( $dtT )->format( $format );
	}

	public static function roundTime( $ts )
	{
		return round( $ts / 60 ) * 60;
	}

	public static function timeToLowestMinute( $ts )
	{
		return floor( $ts / 60 ) * 60;
	}

	public static function timeToHighestMinute( $ts )
	{
		return ceil( $ts / 60 ) * 60;
	}

	public static function datesForDay( $day , $startDateTime , $endDateTime )
	{
		$arr = [];
		$start = strtotime( $startDateTime );
		$end = strtotime( $endDateTime );
		for ( $i = strtotime( $day, $start ); $i <= $end; $i = strtotime( '+1 week', $i ) )
		{
			$arr[] = $i ;
		}
		return $arr ;
	}

	public static function durationHours( $from , $to )
	{
		$tsFrom = new \DateTime( $from );
		$tsTo = new \DateTime( $to );
		$duration = $tsFrom->diff( $tsTo );
		return (int) $duration->format( '%h') + ( $duration->format( '%I') / 60 ) ;

	}

}
