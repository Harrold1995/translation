<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:08 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class sqlFunction
{
	private $params = array() ;

	function __construct( $name ){
		$this->name = $name ;
	}

	function param( $param ) {
		$this->params[] = new sqlFunctionParam( $param ) ;
	}

	function build() {
		$temp = [] ;
		$str = $this->name . '( ' ;
		foreach ( $this->params as $key => $p ) {
			@ $temp[] = $p->build() ;
		}
		$str .= join( ' , ' , @ $temp ) . ' )' ;
		return $str ;
	}
}
