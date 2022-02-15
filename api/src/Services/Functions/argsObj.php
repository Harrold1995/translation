<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:40 PM
 */

namespace API\functions;


class argsObj {
	function add( $param , $val ) {
		if( $param ) $this->{$param} = $val ;
	}

	/**
	 * @param            $param
	 * @param bool|false $default
	 *
	 * @return mixed
	 */
	function get( $param , $default = false )
	{
		return ( @ $this->{ $param } ) ? $this->{ $param } : $default ;
	}
}
