<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:10 PM
 */

// namespace API\sql;
namespace App\Services\Sql;

class join {
	var $primaryKey_table ;
	var $secondaryKey_table ;
	var $primaryKey ;
	var $secondaryKey ;
	var $type = 'inner' ;
	var $alias ;
	var $multiple = array() ;

	function __construct( $primaryKey = '' , $secondaryKey = '' , $skey_table = '' , $pkey_table = '' ) {
		$this->primaryKey = $primaryKey ;
		$this->secondaryKey = $secondaryKey ;
		$this->secondaryKey_table = $skey_table ;
		$this->primaryKey_table = $pkey_table ;
	}

	private function retSecondaryKeyTable() {
		return ( empty ( $this->alias ) ) ? $this->secondaryKey_table : $this->alias ;
	}

	public function addJoin( $prim, $secon = false , $hardwired = '' , $op = '=' , $primaryOveride = false  ) {
		if ( ! $secon ) $secon = $prim ;
		$stbl = $this->retSecondaryKeyTable() ;
		if ( ! $this->multiple ) $this->multiple[] = "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
		if ( $hardwired ) {
			$this->multiple[] = "$stbl.`$secon` $op '$hardwired'" ;
		} else {
			$tbl = ( $primaryOveride ) ? $primaryOveride : $this->primaryKey_table ;
			$this->multiple[] = "$tbl.`$prim` $op $stbl.`$secon`";
		}
	}

	public function addJoinPrimaryOveride( $prim , $secon , $overide , $op = '=' ) {
		$this->addJoin( $prim , $secon , '' , $op , $overide ) ;
	}

	function addHard( $secon , $hardwired , $op = '=' , $quoted = "'" ) {
		$stbl = $this->retSecondaryKeyTable() ;
		$this->multiple() ;
		$this->multiple[] = "$stbl.`$secon` $op {$quoted}$hardwired{$quoted}" ;
	}

	function multiple() {
		if ( ! $this->multiple ) {
			$stbl = $this->retSecondaryKeyTable() ;
			if ( ! $this->primaryKey_table && ! $this->primaryKey ) return ;
			$this->multiple[] = "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
		}
	}

	public function addJoinCalc ( $calc ) {
//        $stbl = ( empty ( $this->alias ) ) ? $this->secondaryKey_table : $this->alias ;
//        if ( ! $this->multiple ) $this->multiple[] = "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
		$this->multiple[] = $calc ;
	}

	function build() {
		if ( empty ( $this->alias ) ) {
			$stbl = $this->secondaryKey_table ;
			$alias = '' ;
		} else {
			$stbl = $this->alias ;
			$alias = 'as ' . $this->alias ;
		}
		$val = ( $this->multiple ) ? join( ' and ' , $this->multiple ) : "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
		return "{$this->type} join {$this->secondaryKey_table} $alias on $val" ;
	}
}

