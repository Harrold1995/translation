<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:15 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class groupBy {
	var $table ;
	var $fld ;

	function __construct( $fld , $table ) {
		$this->table = $table ;
		$this->fld = $fld ;
	}

	function build() {
		$tbl = ( $this->table ) ? $this->table . '.' : '' ;
		return "{$tbl}`{$this->fld}`" ;
	}
}
