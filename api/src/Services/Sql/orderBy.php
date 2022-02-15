<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:12 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class orderBy {
	var $table ;
	var $isCalc ;

	function __construct ( $fld , $sort = 'ASC' ) {
		$this->fld = $fld ;
		$this->sort = $sort ;
	}

	function build() {
		$tbl = ( $this->table ) ? $this->table . '.' : '' ;
		$val = ( $this->isCalc ) ? $this->fld : "`{$this->fld}`" ;
		return ( strtolower( $this->sort ) == "desc" ) ? "$tbl{$val} DESC" : "$tbl{$val}" ;
	}
}
