<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 3:56 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class where
{
	var $table;
	var $field;
	var $value;
	var $op = '=';
	var $quoted = "'";
	var $isBoolean = false;
	var $isCalc = false;        //	Boolean if true the field part of this where statement is a calculated result
	var $not = false;
	var $valueTo;
	var $binary;

	function __construct( $fldn , $val , $tbl , $op = '=' )
	{
		$this->table = $tbl;
		$this->value = $val;
		$this->op = $op;
		$this->parseFld( $fldn );
		//	Switch statement allows us to set up where based on special criteria in the value part ie $obj->where( fld , '[criteria]' )
		if ( $this->is_a_field( $val ) )
		{
			$this->quoted = '';
			$this->value = $this->check_for_field( $val );
		}
		else
		{
			switch ($val)
			{
				case '[bool]' :
					$this->isBoolean = true;
					$this->value = '';
					break;
				case '[boolean]' :
					$this->isBoolean = true;
					$this->value = '';
					break;
				case '[isBoolean]' :
					$this->isBoolean = true;
					$this->value = '';
					break;
				case '[true]' :
					$this->isBoolean = true;
					$this->value = '';
					break;
				case '[false]' :
					$this->isBoolean = true;
					$this->value = '';
					$this->not = true;
					break;
				case '[isEmpty]' :
					$this->empty_def();
					break;
				case '[empty]' :
					$this->empty_def();
					break;
				case '[notEmpty]' :
					$this->empty_def( true );
					break;
				case '[null]' :
					$this->quoted = '';
					$this->value = 'null';
					$this->op = ' is ';
					break;
				case '[notNull]' :
					$this->quoted = '';
					$this->value = 'null';
					$this->op = ' is not ';
					break;
				case '[notnull]' :
					$this->quoted = '';
					$this->value = 'null';
					$this->op = ' is not ';
					break;
				case '[curdate]' :
					$this->value = 'CURDATE()';
					$this->quoted = '';
					break;
				default :
					$this->parseVal();    // See next function for what happens here
			}
		}
	}

	//	Processed after switch statement that checks to see if val exactly matches certain criteria
	//	parseVal looks for certain strings in the val, strips them out and performs tasks based on the search string found
	function parseVal( $val = false )
	{
		if ( $val )
		{
			$val = $this->parseVal_sub( 'calc' , array('<' , '>') , $val );
			$val = $this->parseVal_sub( 'calc' , array('[' , ']') , $val );
			$val = $this->parseVal_sub( 'noquote' , array('[' , ']') , $val );
			$val = $this->parseVal_sub( 'number' , array('[' , ']') , $val );
			$val = $this->parseVal_sub( 'binary' , array('[' , ']') , $val );
			return $val;
		}
		$this->parseVal_sub( 'calc' , array('<' , '>') );
		$this->parseVal_sub( 'calc' );
		$this->parseVal_sub( 'noquote' );
		$this->parseVal_sub( 'number' );
		$this->parseVal_sub( 'binary' );
		// This switch is for setField which extends this class as the function where above is not called
		switch (strtolower( $this->value ))
		{
			case 'null' :
				$this->quoted = '';
				$this->op = ' is ';
				break;
			case '[null]' :
				$this->value = 'null';
				$this->quoted = '';
				$this->op = ' is ';
				break;
			case '[emptystring]' :
				$this->value = '';
				break;
		}
		return $this;
	}

	/**
	 * @param string $pv
	 * @param array $bket
	 * @param String $val
	 *
	 * @return string
	 */
	private function parseVal_sub( $pv , $bket = array('[' , ']') , $val = false )
	{
		$binary = ( $pv == 'binary' );
		$pv = $bket[ 0 ] . $pv . $bket[ 1 ];
		if ( $val )
		{
			if ( strpos( $val , $pv ) !== false )
			{
				$val = str_replace( $pv , '' , $val );
				if ( $binary ) $val = "binary '$val'";
			}
			return $val;
		}
		elseif ( strpos( $this->value , $pv ) !== false )
		{
			$this->value = str_replace( $pv , '' , $this->value );
			if ( $binary ) $this->value = "binary '{$this->value}'";
			$this->quoted = '';
		}
		return $this;
	}

	//	parseFld
	//	By placing <calc> in the field name we can define this field as a calc field.
	//	Also if there are opening and closing parenthesis '(' and ')' in the field def then we can assume this a calc field as it must contain a function
	//	Also look for [bin] or [binary] and set this field to be cast to binary
	function parseFld( $fldn )
	{
		if ( $this->is_a_field( $fldn ) )
		{
			$this->isCalc = true;
		}
		elseif ( strpos( $fldn , '<calc>' ) !== false )
		{
			$fldn = str_replace( '<calc>' , '' , $fldn );
			$this->isCalc = true;
		}
		elseif ( strpos( $fldn , '[calc]' ) !== false )
		{
			$fldn = str_replace( '[calc]' , '' , $fldn );
			$this->isCalc = true;
		}
		elseif ( strpos( $fldn , '[bin]' ) !== false )
		{
			$fldn = str_replace( '[bin]' , '' , $fldn );
			$this->binary = true;
		}
		elseif ( strpos( $fldn , '[binary]' ) !== false )
		{
			$fldn = str_replace( '[binary]' , '' , $fldn );
			$this->binary = true;
		}
		elseif ( strpos( $fldn , '(' ) !== false && strpos( $fldn , ')' ) !== false )
		{
			$this->isCalc = true;
		}
		$this->field = $this->check_for_field( $fldn );
	}

	function empty_def( $not = false )
	{
		//	Relies on empty function being defined in mysql is as follows
		/*  accepts 1 parameter `data` varchar(255) and returns tinyint(1)
			BEGIN
				if( data is null || data = '' ) THEN
					return 1 ;
				else
					return 0 ;
				end if ;
			END
		*/
		$rev = ( $not ) ? '! ' : '';
		$this->field = $rev . "empty( " . $this->field_def() . " )";
		$this->isCalc = true;
		$this->isBoolean = true;
		$this->value = '';
	}

	private function field_def()
	{
		return $this->table_def( true );
	}

	private function table_def( $incField = false )
	{
		$tbl = ( $this->table ) ? $this->table . '.' : '';
		$bin = ( $this->binary ) ? 'binary ' : '';
		return ( $incField ) ? $bin . $tbl . "`" . $this->field . "`" : $tbl;
	}

	function set()
	{
		global $mysqli;
		$fldDef = ( $this->isCalc ) ? $this->field : $this->table_def( true );
		$val = ( empty ( $this->quoted ) ) ? $this->value : $mysqli->real_escape_string( $this->value );
		$val = $this->quoted . $val . $this->quoted;
		if ( @ $this->valueTo )
		{
			$v2 = $this->parseVal( $this->valueTo );
			if ( $v2 != $this->valueTo )
			{
				//  There has been a [calc] or [binary] etc in the value to. Leave as is
			}
			else
			{
				$v2 = $this->quoted . ( ( empty ( $this->quoted ) ) ? $this->valueTo : $mysqli->real_escape_string( $this->valueTo ) ) . $this->quoted;
			}
			return "$fldDef between $val and $v2";
		}
		else
		{
			return $fldDef . $this->op . $val;
		}
	}

	function between( $from , $to )
	{
		$this->value = $from;
		$this->parseVal();
		$this->valueTo = $to;
	}

	private function is_a_field( $fld )
	{
		return $this->check_for_field( $fld , true );
	}

	private function check_for_field( $fld , $bool = false )
	{
		$isField = is_a( $fld , field::class );
		if ( $bool )
		{
			return ( $isField ) ? true : false;
		}
		else
		{
			/* @var $fld \API\sql\field */
			return ( $isField ) ? $fld->def() : $fld;
		}
	}

	function build()
	{
		$revBool = ( $this->not ) ? 'not ' : '';
		if ( $this->check_for_field( $this->field , true ) )
		{
			if ( $this->isBoolean )
			{
				return $revBool . $this->field->def();
			}
			else
			{
				return $this->field->def() . " {$this->op} " . $this->quoted . ( ( empty ( $this->quoted ) ) ? $this->value : sval( $this->value ) ) . $this->quoted;
			}
		}
		if ( $this->isBoolean )
		{
			$tbl = ( $this->table ) ? $this->table . '.' : '';
			// Add tbl to field return of following statement and check to see nothing breaks
			return $revBool . ( ( $this->isCalc ) ? $this->field : $tbl . "`" . $this->field . "`" );
		}
		else
		{
			return $this->set();
		}
	}

}
