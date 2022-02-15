<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 3:55 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class setField extends where {
	function __construct( $fldn , $val , $tbl ){
		$this->table = $tbl ;
		$this->field = $fldn ;
		$this->value = $val ;
		$this->parseVal() ;
		$this->op = '=' ;
	}
}
