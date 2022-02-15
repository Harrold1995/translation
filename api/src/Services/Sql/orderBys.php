<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:12 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class orderBys {

	function addOrderBy( $fld , $sort = 'ASC' , $table = false ) {
		$this->{$fld} = new orderBy( $fld , $sort ) ;
		if ( $table ) $this->{$fld}->table = $table ;
		return $this->{$fld} ;
	}

	function build(  ) {
		$pass = false ;
		$temp = [] ;
		foreach ( $this as $key => $fld ) {
			/* @var $fld \API\sql\orderBy */
			$pass = true ;
			@ $temp[] = $fld->build() ;
		}
		return ( $pass ) ? "\n order by " . join ( ',' , $temp ) : '' ;
	}
}

