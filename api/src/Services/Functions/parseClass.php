<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 20/2/17
 * Time: 4:01 PM
 */

// namespace API\functions;
namespace App\Services\Functions;


class parseClass
{
	static function name( $object )
	{
		$cname = ( is_string( $object ) ) ? $object : get_class( $object ) ;
		$arr = explode( '\\' , $cname ) ;
		return array_pop( $arr ) ;
	}

	/**
	 * @param $object
	 *
	 * @return bool|string
	 */
	static function name_space( $object )
	{
		$arr = explode( '\\' , get_class( $object ) ) ;
		array_pop( $arr );
		if( $arr )
		{
			$ns = implode( '\\' , $arr ) ;
		}
		else
		{
			$ns = '\\';
		}
		return $ns ;
	}

}