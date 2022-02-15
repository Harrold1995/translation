<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:03 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class field {
	var $table ;
	var $name ;
	var $alias ;
	var $isCalc ;
	var $include = true ;
	var $applyRounding ;
	var $roundTo = 2 ;
	var $sum ;			// ( boolean ) Indicates that this field is to be a sum of the numeric field value
	var $avg ; 			// ( boolean ) Indicates that this field is to be an average of the numeric field value
	var $maxval ; 		// ( boolean ) Indicates that this field is to be the maximum value of the field value
	/* @var $subQuery request */
	var $subQuery ;
	var $case ;
	var $funcWrapper ;	// New way of processing min max sum etc see checkDirectives and wrapFunc for how this is applied

	function __construct( $tbl = '' , $name = '' , $alias = '' ) {
		$this->table = $tbl ;
		$name = $this->check_for_field( $name ) ;
		$name = $this->checkDirectives( $name ) ;
		$alias = $this->checkDirectives( $alias ) ;
		if ( ( $pos = strpos( $name , '[round:' ) ) !== false ) {
			$this->applyRounding = true ;
			$p = substr( $name , $pos , strpos( $name , ']' , $pos ) - $pos + 1 ) ;
			$ps = explode( ':' , trim( $p , '][' ) ) ;
			$this->roundTo = $ps[1] ;
			$name = str_replace( $p , '' , $name ) ;
		}
		if ( strpos( $name , '(' ) !== false && strpos( $name , ')' ) !== false ) $this->isCalc = true ;
		$this->name = $name ;
		$this->alias = $alias ;
	}

	function checkDirectives( $name ) {
		$preg = '#\[.{2,}?\]#' ;
		preg_match( $preg , $name , $matches ) ;
		if ( $matches ) {
			$func = trim( $matches[0] , '][' ) ;
			$checkRounding = ( $func == 'round' ) ? ", %d" : '' ;
			$params = explode( ':' , $func ) ;
			if ( count( $params ) > 1 ) {
				$func = array_shift( $params ) ;
				$checkRounding = str_replace( '%' , '[perc]' , ", " . implode( ' , ' , $params ) ) ;
			}
			$this->funcWrapper = ( $this->funcWrapper ) ? "$func( {$this->funcWrapper} $checkRounding )" : "$func( %s $checkRounding )" ;
			return $this->checkDirectives( preg_replace( $preg , '' , $name , 1 ) ) ;
		} else {
			return $name ;
		}
	}

	function setCalc( $calc )
	{
		$this->name = $calc ;
		$this->isCalc = true ;
	}

	function addCalc( $fld1 = '' , $op = '' , $fld2 = '' ) {
		$this->isCalc = true ;
		$this->name = $this->check_for_field( $fld1 ) ;
		$this->name .= ( $op ) ? " $op " : '' ;
		$this->name .= $this->check_for_field( $fld2 ) ;
	}

	private function caseConditionTypeCheck( $obj ) {
		if ( $this->check_for_field( $obj , true ) ) {
			return $this->check_for_field( $obj ) ;
		} elseif( is_numeric( $obj ) ) {
			return $obj ;
		} else {
			return "'$obj'" ;
		}
	}

	function addCaseCondition( $left , $op = '=' , $right , $then ) {
		$left = $this->caseConditionTypeCheck( $left ) ;
		$right = $this->caseConditionTypeCheck( $right ) ;
		$then = $this->caseConditionTypeCheck( $then ) ;
		$if = "$left $op $right" ;
		$this->name .= "\n\t\tWHEN $if THEN $then " ;
	}

	function addCaseElse( $else ) {
		$else = $this->caseConditionTypeCheck( $else ) ;
		$this->name .= "\n\t\tELSE $else" ;
	}

	function addFunction( $f ) {
		$this->name = "$f( {$this->name} )" ;
	}

	function json( $array )
	{
		$arr = array() ;
		$arr[] = "'{'" ;
		foreach ( $array as $key => $val ) {
			if( $this->check_for_field( $val , true ) )
			{
				$pre = ( $key ) ? "'" . '",' : "'" ;
				$arr[] = $pre . '"' . ( ( $val->alias ) ? $val->alias : $val->name ) . '":"' . "'" ;
				$arr[] = $this->check_for_field( $val ) ;
			}
		}
		$arr[] = "'" . '"}' . "'" ;
		$this->concat( $arr ) ;
	}

	function concat( $array ) {
		foreach ( $array as $key => &$val ) $val = $this->check_for_field( $val ) ;
		$this->name = "concat( " . implode( ' , ' , $array ) . ' )' ;
		$this->isCalc = true ;
	}

	function groupConcat( $field )
	{
		$this->name = 'GROUP_CONCAT(' . $this->check_for_field( $field ) . ')' ;
		$this->isCalc = true ;
	}

	function genFunction( $func , $params ) {
		$a = [] ;
		foreach ( $params as $key => $param ) @ $a[] = $this->check_for_field( $param ) ;
		$this->name = "$func( " . implode( ' , ' , $a ) . " )" ;
		$this->isCalc = true ;
	}

	function datediff( $from , $less ) { $this->genFunction( 'datediff' , array( $from , $less ) ) ; }
	function timestampdiff( $from , $to , $units = 'SECOND' ) { $this->genFunction( 'timestampdiff' , array( $units , $from , $to ) ) ; }

	function addIf( $cond , $iftrue , $iffalse ) {
		$this->isCalc = true ;
		$cond = $this->check_for_field( $cond ) ;
		$iftrue = $this->check_for_field( $iftrue ) ;
		$iffalse = $this->check_for_field( $iffalse ) ;
		$this->name = "if( $cond , $iftrue , $iffalse )" ;
	}

	function ifnull( $cond , $result_if_null ) {
		$this->isCalc = true ;
		$this->name = "ifnull( " . $this->check_for_field( $cond ) . ' , ' . $this->check_for_field( $result_if_null ) . ' )' ;
	}

	private function check_for_field( $fld  , $bool = false ) {
		$isField = is_a( $fld , field::class );
		if ( $bool ) {
			return ( $isField ) ? true : false ;
		} else {
			if ( $isField )
			{
				/* @var $fld \API\sql\field */
				return $fld->def() ;
			}
			else
			{
				return $fld ;
			}
		}
	}

	function encapsulate( $c1 = '(' , $c2 = ')' ) {
		return $c1 . $this->name . $c2 ;
	}

    function addSubQuery( $tbl )
    {
        if ( is_a( $tbl , request::class ) )
        {
            $this->subQuery = $tbl;
        }
        else
        {
            $this->subQuery = new request( $tbl );
        }
        return $this->subQuery;
    }

	private function applyCase( $def ) {
		switch ( $this->case ) {
			case 'lower' : return "LOWER( $def )" ; break ;
			case 'upper' : return "UPPER( $def )" ; break ;
			default : return $def ;
		}
	}

	function retTableName() {
		$table = ( $this->table ) ? "{$this->table}." : '' ;
		$tilde = ( $this->name == '*' ) ? '' : '`' ;
		return  ( $this->isCalc ) ? $this->name : $table . $tilde . $this->name . $tilde ;
	}

	function def() {
		if( $this->subQuery ) return $this->wrapFunc( '( ' . $this->subQuery->retSQL() . ' )' ) ;
		if ( $this->case && ! $this->alias ) $this->alias = $this->name ;
		return $this->wrapFunc(  $this->applyCase( $this->retTableName() ) ) ;
	}

	function wrapFunc( $val ) {
		$func = '' ; $close = '' ;
		if ( $this->sum ) {
			$func = 'sum( ' ; $close = ' )' ;
		} elseif( $this->avg ) {
			$func = 'avg( ' ; $close = ' )' ;
		} elseif( $this->maxval ) {
			$func = 'max( ' ; $close = ' )' ;
		}
		if( $this->funcWrapper ) return str_replace( '[perc]' , '%' , sprintf( $this->funcWrapper , $val , $this->roundTo ) ) ;
		return ( $this->applyRounding ) ? "round( $func{$val}$close , {$this->roundTo} )" : "$func{$val}$close" ;
	}

	function build() {
		if ( $this->subQuery ) return $this->def()  . ' as ' . ( ( @ $this->alias ) ? request::tilde($this->alias) : $this->name ) ;
		$alias = ( $this->alias ) ? " as `{$this->alias}`" : '' ;
		return  $this->def() . $alias ;
	}
}

