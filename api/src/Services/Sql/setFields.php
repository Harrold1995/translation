<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 3:54 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class setFields
{
	/* @var $setFields \API\sql\setField[] */
	var $setFields ;
	/* @var $dupFields \API\sql\setField[] */
	var $dupFields ;

	function add ( $fld , $val , $tbl , $dup = true ) {
		$this->setFields[ $fld ] = new setField ( $fld , $val , $tbl ) ;
		if ( $dup ) $this->dupFields[ $fld ] = $this->setFields[ $fld ] ;
		return $this->setFields[ $fld ] ;
	}

	function build() {
		if ( $this->setFields ) {
			$temp = [] ;
			foreach ( $this->setFields as $key => $sf ) {
				@ $temp[] = $sf->build() ;
			}
			return " set " . join( ' , ' , $temp ) ;
		} else {
			return '' ;
		}
	}

	function buildOnDuplicate() {
		if ( $this->dupFields ) {
			$temp = [] ;
			foreach ( $this->dupFields as $key => $sf ) {
				@ $temp[] = $sf->build() ;
			}
			return " on duplicate key update " . join( ' , ' , $temp ) ;
		} else {
			return '' ;
		}
	}

}
