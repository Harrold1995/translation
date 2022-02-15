<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:14 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class groupParse {
	private $total ;

	function &add( $key , $row ) {
		if ( ! @ $this->{$key . '.' . $row->{$key}} ) $this->{$key . '.' . $row->{$key}} = new groupParse ;
		$this->{$key . '.' . $row->{$key}}->total = $row ;
		return $this->{$key . '.' . $row->{$key}} ;
	}

	function addTotal( $row ) {
		$this->total = $row ;
	}

	function getTotal ( $fld = '' , $curr = '' ) {
		if ( $fld ) {
			$t  = $this->total->{$fld} ;
		} else {
			$t = $this->total ;
		}
		return ( $curr ) ? $curr . number_format ( (float) $t , 2 ) : $t ;
	}

	function getData ( $fld = '' , $curr = '' ) {
		return $this->getTotal( $fld , $curr ) ;
	}

	function percentDiff( $orig , $compTo , $rev = false ) {
		$perc = round( ( ( $this->getData( $orig ) - $this->getData( $compTo ) ) / $this->getData( $orig ) ) * 100 , 2 ) . '%';
		return ( $rev ) ? round( 100 - $perc , 2 ) . '%' : $perc ;
	}

	function elementTotal ( $mkey , $fld , $curr = '' ) {
		$sum = array() ;
		foreach ( $this as $key => $obj ) {
			if ( $key != 'total' ) {
				if ( @ $obj->{$mkey} ) $sum[] = $obj->{$mkey}->getTotal( $fld ) ;
			}
		}
		return ( $curr ) ? $curr . number_format ( (float) array_sum ( $sum ) , 2 ) : array_sum ( $sum ) ;
	}
}
