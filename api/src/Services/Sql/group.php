<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:15 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class group {
	/* @var $groups groupBy[] */
	var $groups = array();
	var $rollup ;
	var $parseData;

	function __construct() {
		$this->parseData = new groupParse ;
	}

	function addGroup( $fld , $table ) {
		$this->groups[$fld] = new groupby( $fld , $table ) ;
		return $this->groups[$fld] ;
	}

	function build() {
		if ( $this->groups ) {
			$temp = [] ;
			foreach ( $this->groups as $key => $group ) {
				$temp[] = $group->build() ;
			}
			return "\n group by " . join ( ',' , $temp ) . ( ( $this->rollup ) ? ' with rollup ' : '' ) ;
		} else {
			return '' ;
		}
	}

	function parse( $row ) {
		$notProcessed = true ;
		$gp = $this->parseData ;
		foreach ( $this->groups as $key => $group ) {
			if ( @ $row->{$key} ) {
				$notProcessed = false ;
				$gp = $gp->add( $key , $row ) ;
			}
		}
		if ( $notProcessed ) $this->parseData->addTotal( $row ) ;
	}
}

