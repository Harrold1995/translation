<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:03 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class fields {
	/* @var $fields \API\sql\field[] */
	var $fields ;

	/**
	 * @param string $tbl
	 * @param string $name
	 * @param string $alias
	 *
	 * @return field
	 */
	function addField( $tbl = '' , $name = '' , $alias = '' ) {
		$fld = ( $alias ) ? $alias : ( ( $tbl ) ? $tbl .'_' . $name : $name ) ;
		$this->fields[ $fld ] = new field( $tbl , $name , $alias ) ;
		return $this->fields[ $fld ] ;
	}

	function removeField( $fld ) {
		unset( $this->fields[ $fld ] ) ;
	}

	function build(){
		if ( $this->fields ) {
			$temp = [] ;
			foreach ( $this->fields as $key => $field ) {
				/* @var $field \API\sql\field */
				if ( $field->include ) @ $temp[] = $field->build() ;
			}
			return "\n\t" . join( ",\n\t"  , $temp ) . "\n" ;
		} else {
			return '*' ;
		}
	}
}
