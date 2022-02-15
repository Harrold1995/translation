<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:10 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class joins {
	/* @var $joins join[] */
	var $joins ;
	function __construct() {

	}

	/**
	 * @param $primaryKey
	 * @param $secondaryKey
	 * @param $skey_table
	 * @param $pkey_table
	 *
	 * @return join
	 */
	function addJoin( $primaryKey , $secondaryKey , $skey_table , $pkey_table ) {
		$this->joins[] = new join( $primaryKey , $secondaryKey , $skey_table , $pkey_table ) ;
		return $this->joins[ count ( $this->joins ) - 1 ] ;
	}

	function build() {
		if ( $this->joins ) {
			$temp = [] ;
			foreach ( $this->joins as $key => $join ) {
				$temp[] = $join->build() ;
			}
			return " \n" . join( " \n"  , $temp ) ;
		} else {
			return '' ;
		}
	}
}
