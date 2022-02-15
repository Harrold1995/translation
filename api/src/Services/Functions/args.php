<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:39 PM
 */

// namespace API\functions;
namespace App\Services\Functions;


class args {

	/*
		Enhanced form of parseArgs
		Handling of arrays is improved so that you can pass arrays as key=>value pairs and allows multiple args in a single array
		In the function declaration immediately after the opening {
		add
		$a = func_get_args(); $args = \args::parse($a);
	*/
	static function parse( $args ) {
		if( @ $args->pass ) {
			return $args;
		} elseif ( @ $args[0]->pass ) {
			return $args[0] ;
		} else {
			$argobj = new argsObj ;
			foreach( $args as $key => $arg ) {
				if( is_array( $arg ) ) {
					foreach( $arg as $param => $val ){
						$argobj->add( $param , $val ) ;
					}
				} else {
					$a = explode( ":", $arg,2);
					$argobj->add( $a[0] , $a[1] ) ;
				}
			}
			return $argobj;
		}
	}

}

