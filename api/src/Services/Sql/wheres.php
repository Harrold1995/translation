<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 3:56 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class wheres
{
	/* @var $wheres \API\sql\where[] */
	public $wheres;
	public $type = 'where';
	public $op = 'and';
	public $booleanCalc = false ;

	public function __toString()
	{
		return $this->build() ;
	}

	function addWhere( $fld , $val , $tbl , $id = '' , $op = '=' )
	{
		$f = 'field';
//		is_a( $fld , field::class );
//		if ( $fld instanceof $f && ! $id ) {
		if ( is_a( $fld , field::class ) && !$id )
		{
			$key = ( $fld->alias ) ? $fld->alias : $fld->name;
		}
		else
		{
			$key = ( $id ) ? $id : $fld;
		}
		$this->wheres[ $key ] = new where ( $fld , $val , $tbl , $op );
		return $this->wheres[ $key ];
	}

	public function in( $fld , array $valsArray , $table = '' )
	{
		$val = "('" . implode( "','" , $valsArray ) . "')[calc]";
		$this->addWhere( $fld , $val , $table , '' ,' in ' );
	}


	function build()
	{
		if ( $this->wheres )
		{
			$temp = [];
			foreach ( $this->wheres as $key => $where )
			{
				@ $temp[] = $where->build();
			}
			if ( $this->booleanCalc )
			{
				return '( ' . join( "\n\t{$this->op} " , $temp ) . ' )';
			}
			else
			{
				return " \n {$this->type} \n\t" . join( "\n\t{$this->op} " , $temp );
			}
		}
		else
		{
			return '';
		}
	}
}
