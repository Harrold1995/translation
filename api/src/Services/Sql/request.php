<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 3:44 PM
 */

// namespace API\sql;

// namespace App\Sql;
namespace App\Services\Sql;


use App\Functions\args;
use App\Functions\debug;

class request
{
	var $table;
	var $action;
	var $fieldList = array();
	/* @var $where wheres */
	var $where;
	/* @var $having wheres */
	var $having;
	var $fields;
	/* @var $joins joins */
	var $joins;
	var $setFields;
	var $dupFields;
	var $dupField = array();
	/* @var $groupBy group */
	var $groupBy;
	var $orderBy;
	var $sql;
	var $error;
	var $found;
	var $affected;
	var $rows = array();
	var $fetchObj = requestObj::class;
	var $fetchObjParams;            // Needs to be an array. Mostly useful when utilising a class or refenced variable where the fetchobj can add to alter etc
	var $rowKeyField;
	var $rowKeyPrefix;
	var $last_insert_id;
	/* @var $rowObjToThis bool */
	var $rowObjToThis;
	var $recordLimit;
	var $limitOffset;
	var $distinct;
	var $alias;
	var $debug;
	var $dbServer;
	var $dbDatabase;
	var $dbUser;
	var $dbPass;
	public $Columns;
	public $forceDbChange;

	function __construct( $table = '' , $act = 'select' )
	{
		$this->table = $table;
		$this->action = $act;
		$this->fields = new fields();
		$this->dbDatabase = DB_DATABASE;
		if ( $table ) $this->primaryKeyField = $this->retPrimaryKeyField();
	}

	public function dataBase( $db )
	{
		$this->dbDatabase = $db ;
	}

	public static function tilde( $val )
	{
		return "`$val`";
	}

	//	Functions for our search queiries

	function clearWhere()
	{
		$this->where = new wheres;
	}

	function clearHaving()
	{
		$this->having = new wheres;
	}

	private function table_alias()
	{
		return ( $this->alias ) ? $this->alias : $this->table;
	}

	/**
	 * Check where or having exists.
	 *
	 * Checks that the property $type exists and is a class wheres and if not creates a new wheres instance assigned to the $type property.
	 *
	 * @param string $type
	 */
	private function checkWhereExists( $type = 'where' )
	{
		if ( !is_a( $this->{$type} , wheres::class ) ) $this->{$type} = new wheres;
		$this->{$type}->type = $type;
	}

	/**
	 * @param string $fld
	 * @param string $val
	 * @param string $op
	 * @param string $tbl
	 * @param string $id
	 *
	 * @return where
	 */
	function where( $fld , $val = '' , $op = '=' , $tbl = '' , $id = '' )
	{
		return $this->addWhere( $fld , $val , $op , $tbl , $id );
	}

	/**
	 * Adds a new where class.
	 *
	 * @param string $fld
	 * @param string $val
	 * @param string $op
	 * @param string $tbl
	 * @param string $id
	 *
	 * @return where
	 */
	function addWhere( $fld , $val = '' , $op = '=' , $tbl = '' , $id = '' )
	{
		$this->checkWhereExists();
		$tbl = ( $tbl ) ? $tbl : $this->table_alias();
		$wh = $this->where->addWhere( $fld , $val , $tbl , $id );
		if ( $op != '=' ) $wh->op = $op;
		if ( $val == '[boolean:true]' ) $wh->isBoolean = true;
		return $wh;
	}

	function addWhereById( $id , $fld , $val = '' , $op = '=' , $tbl = '' )
	{
		return $this->where( $fld , $val , $op , $tbl , $id );
	}

	function in( $fld , array $valsArray , $table = '' )
	{
		$val = "('" . implode( "','" , $valsArray ) . "')[calc]";
		$this->where( $fld , $val , ' in ' , $table );
	}

	private function remove_search( $fld , $type = 'where' )
	{
		$w = 'wheres';
		if ( $this->{$type} instanceof $w )
		{
			unset( $this->{$type}->wheres[ $fld ] );
		}
	}

	function remWhere( $fld )
	{
		$this->remove_search( $fld );
	}

	function remHaving( $fld )
	{
		$this->remove_search( $fld , 'having' );
	}

	function between( $fld , $from , $to , $table = false )
	{
		$wh = $this->where( $fld );
		if ( $table ) $wh->table = $table;
		$wh->between( $from , $to );
		return $wh;
	}

	function havingById( $id , $fld , $val = '' , $op = '=' , $tbl = '' )
	{
		$this->checkWhereExists( 'having' );
		$wh = $this->having->addWhere( $fld , $val , $tbl , $id , $op );
		return $wh;
	}

	function having( $fld , $val = '' , $op = '=' , $tbl = '' )
	{
		$this->checkWhereExists( 'having' );
		$wh = $this->having->addWhere( $fld , $val , $tbl );
		$wh->op = $op;
		return $wh;
	}

	//	Functions for sorting the data

	function addOrderBy( $fld , $sort = 'ASC' , $table = false )
	{
		if ( ! is_a( $this->orderBy , orderBys::class ) ) $this->orderBy = new orderBys;
		return $this->orderBy->addOrderBy( $fld , $sort , $table );
	}

	function addSort( $fld , $sort = 'ASC' , $table = false )
	{
		return $this->addOrderBy( $fld , $sort , $table );
	}

    public function addSortArray( array $array )
    {
        foreach ( $array as $item )
        {
            $this->addOrderBy( $item );
        }
    }


    function clearSort()
	{
		$this->orderBy = false;
	}

	//	Function for working with SQLs GROUP BY statement

	function addGroupBy( $fld , $tbl = '<none>' )
	{
		//$f = 'group';
		//if ( !( $this->groupBy instanceof $f ) ) $this->groupBy = new group;
        if ( ! is_a( $this->groupBy , group::class ) ) $this->groupBy = new group;
		$this->groupBy->addGroup( $fld , ( $tbl == '<none>' ) ? $this->table : $tbl );
	}

	function clearGroupBy()
	{
		$this->groupBy = new group;
	}

	function rollup()
	{
		$f = 'group';
		if ( !is_a( $this->groupBy , groupBy::class ) ) $this->groupBy = new group;
//		if ( ! ( $this->groupBy instanceof $f ) ) $this->groupBy = new group ;
		$this->groupBy->rollup = ( @ $this->groupBy->rollup ) ? false : true;
	}

	/**
	 * @return groupParse
	 */
	function groupData()
	{
		return $this->groupBy->parseData;
	} //	Provides easy access to this data

	//	For working with fields in sql staetments.

	function clearSetFields()
	{
		$this->setfields = false;
	}

	function clearAddFields()
	{
		$this->fields = '';
	}


	/**
	 * @param string $fld
	 * @param string $val
	 * @param bool $dup
	 * @param bool $tbl
	 *
	 * @return mixed
	 */
	function setField( $fld , $val , $dup = true , $tbl = false )
	{
		if ( !$tbl ) $tbl = $this->table;
		if ( !is_a( $this->setFields , setFields::class ) ) $this->setFields = new setFields();
		$f = $this->setFields->add( $fld , $val , $tbl , $dup );
		return $f;
	}

	function getSetField( $fld )
	{
		return @ $this->setFields->setFields[ $fld ]->value;
	}

	/**
	 * @param string $fld
	 * @param string $tbl
	 * @param string $alias
	 *
	 * @return field
	 */
	function addField( $fld = '' , $tbl = '' , $alias = '' )
	{
		if ( ! is_a( $this->fields , fields::class ) ) $this->fields = new fields;
		return $this->fields->addField( ( $tbl ) ? $tbl : $this->table_alias() , $fld , $alias );
	}

	function addCount( $alias = 'num' )
	{
		$fld = $this->addFieldAlias( $alias , 'count(*)' );
		return $fld;
	}

	function addFieldArray( $arr )
	{
		foreach ( $arr as $key => $fld )
		{
            if ( is_array( $fld ) )
            {
                if ( $fld[ 'alias' ] )
                {
                    $this->addFieldAlias( $fld[ 'alias' ] , $fld[ 'field' ] );
                }
                else
                {
                    $this->addField( $fld[ 'field' ] );
                }
            }
            else
            {
                $this->addField( $fld );
            }
		}
	}

	function removeField( $fld )
	{
		$f = 'fields';
		if ( !( $this->fields instanceof $f ) ) return;
		$this->fields->removeField( $fld );
	}

	/**
	 * @param string $alias
	 * @param string $fld
	 * @param string $tbl
	 *
	 * @return field
	 */
	function addFieldAlias( $alias = '' , $fld = '' , $tbl = '' )
	{
		return $this->addField( $fld , $tbl , $alias );
	}

	function addFieldCalc( $alias = '' , $calc = '' )
	{
		$fld = $this->addField( $calc , '' , $alias );
		$fld->isCalc = true;
		return $fld;
	}

	function concat( $alias , $array )
	{
		$fld = $this->addFieldAlias( $alias );
		$fld->concat( $array );
		return $fld;
	}

	/**
	 * @param $alias
	 * @param $cond
	 * @param $result_if_null
	 *
	 * @return field
	 */
	function ifnull( $alias , $cond , $result_if_null )
	{
		$fld = $this->addFieldAlias( $alias );
		$fld->ifnull( $cond , $result_if_null );
		return $fld;
	}

	function addCase( $alias )
	{
		$fld = $this->addFieldCalc( $alias );
		$fld->funcWrapper = "CASE %s \n\tEND";
		return $fld;
	}

	/**
	 * @param string $alias
	 * @param array $array
	 *
	 * @return field
	 */
	function json( $alias , $array )
	{
		$fld = $this->addFieldAlias( $alias );
		$fld->json( $array );
		return $fld;
	}

	function groupConcat( $alias , $field )
	{
		$fld = $this->addFieldAlias( $alias );
		$fld->groupConcat( $field );
		return $fld;
	}

	/**
	 * @param string $alias
	 * @param string $table
	 * @param bool|true $retField
	 *
	 * @return field|request
	 */
	function subQuery( $alias , $table , $retField = true )
	{
		$fld = $this->addFieldAlias( $alias );
		$subq = $fld->addSubQuery( $table );
		return ( $retField ) ? $fld : $subq;
	}

	/**
	 * @param $alias
	 * @param $table
	 *
	 * @return request
	 */
	function retSubQuery( $alias , $table )
	{
		return $this->subQuery( $alias , $table , false );
	}

	//	Working with Joins

	function addJoin( $toTable , $primaryKey , $secondaryKey = false , $fromTable = false )
	{
		//$j = 'joins';
		//if ( !( $this->joins instanceof $j ) ) $this->joins = new joins;
        	if (!is_a($this->joins, joins::class)) $this->joins = new joins;
		if ( !$secondaryKey ) $secondaryKey = $primaryKey;
		return $this->joins->addJoin( $primaryKey , $secondaryKey , $toTable , ( $fromTable ) ? $fromTable : $this->table );
	}

	function addLeftJoin( $toTable , $primaryKey , $secondaryKey = false , $fromTable = false )
	{
		$jn = $this->addJoin( $toTable , $primaryKey , $secondaryKey , $fromTable );
		$jn->type = 'left';
		return $jn;
	}

	/**
	 * @param string $toTable
	 * @param string $primaryKey
	 * @param string $secondaryKey
	 * @param string $fromTable
	 *
	 * @return join
	 */
	function leftJoin( $toTable , $primaryKey , $secondaryKey = false , $fromTable = false )
	{
		return $this->addLeftJoin( $toTable , $primaryKey , $secondaryKey , $fromTable );
	}

	function addJoinHard( $toTable , $keyField , $value , $op = '=' )
	{
		$j = 'joins';
		if ( !( $this->joins instanceof $j ) ) $this->joins = new joins;
		$jn = $this->joins->addJoin( '' , $keyField , $toTable , '' );
		$jn->addHard( $keyField , $value , $op );
		return $jn;
	}

	//	Function join
	//		acepts labelled parameters eg "label:value" or array( "label" => value , [....] ) ;
	//		Parameters are as below. [param] indicates an optional parameter
	//			to			:	The table we are joining to
	//			[from]		:	The table we are joining from. If not specified will use the primary table
	//			pkey		:	The primary key to use for the join
	//			[skey]		:	The secondary key if named differently from the primary key
	//			[alias]		:	Alias for the to table
	//			[type]		:	The type of join to be used. Defaults to inner join
	function Join()
	{
		$a = func_get_args();
		$args = args::parse( $a );
		if ( property_exists( $args , 'to' ) && property_exists( $args , 'pkey' ) )
		{
			if ( @ $args->get( 'val' ) && @ $args->get( 'skey' ) )
			{
				$jn = $this->addJoinHard( $args->get( 'to' ) , $args->get( 'skey' ) , $args->get( 'val' ) , $args->get( 'op' , '=' ) );
			}
			else
			{
				$jn = $this->addJoin( $args->get( 'to' ) , $args->get( 'pkey' ) , @ $args->get( 'skey' ) , @ $args->get( 'from' ) );
			}
			if ( @ $args->get( 'alias' ) ) $jn->alias = $args->get( 'alias' );
			if ( @ $args->get( 'type' ) ) $jn->type = $args->get( 'type' );
			return $jn;
		}
		else
		{
			error_log( 'class standardSQLrequest->Join() error: missing arguments must supply to: and pkey:' );
			return json_decode( '{"error":"Missing parameters"}' );
		}
	}

	// The crux of it getting all this stuff and turning it into a sql statement

	function buildSQL()
	{
		if ( !@ $this->primaryKeyField ) $this->primaryKeyField = $this->retPrimaryKeyField();
//		$f = 'fields' ; $j = 'joins' ; $w = 'wheres' ; $o = 'orderBys' ; $s = 'setfields' ; $g = 'group' ;
		if ( !is_a( $this->fields , fields::class ) ) $this->fields = new fields;
//		if ( ! ( $this->fields instanceof $f ) ) $this->fields = new fields ;
		if ( !is_a( $this->joins , joins::class ) ) $this->joins = new joins;
//		if ( ! ( $this->joins instanceof $j ) ) $this->joins = new joins ;
//		if ( ! ( $this->groupBy instanceof $g ) ) $this->groupBy = new group ;
		if ( !is_a( $this->groupBy , group::class ) ) $this->groupBy = new group;
		if ( !is_a( $this->where , wheres::class ) ) $this->where = new wheres;
		if ( !is_a( $this->having , wheres::class ) ) $this->having = new wheres;
		if ( !is_a( $this->orderBy , orderBys::class ) ) $this->orderBy = new orderBys;
		if ( !is_a( $this->setFields , setFields::class ) ) $this->setFields = new setFields;
//		if ( ! ( $this->where instanceof $w ) ) $this->where = new wheres ;
//		if ( ! ( $this->having instanceof $w ) ) $this->having = new wheres ;
//		if ( ! ( $this->orderBy instanceof $o ) ) $this->orderBy = new orderBys ;
//		if ( ! ( $this->setFields instanceof $s ) ) $this->setFields = new setFields() ;
		$fl = $this->fields->build();
		$jn = $this->joins->build();
		$gp = $this->groupBy->build();
		$wh = $this->where->build();
		$hv = $this->having->build();
		$ob = $this->orderBy->build();
		$sf = $this->setFields->build();
		$df = $this->setFields->buildOnDuplicate();
		$loff = ( $this->limitOffset ) ? ( ( $this->limitOffset == 'start' ) ? "0," : "{$this->limitOffset}," ) : '';
		$lm = ( $this->recordLimit ) ? " limit {$loff}{$this->recordLimit}" : '';
		//	$cfr = ( $this->recordLimit ) ? 'SQL_CALC_FOUND_ROWS' : '' ;  // not working due to being applied in subqueries
		$dist = ( $this->distinct ) ? 'DISTINCT' : '';
		$alias = ( $this->alias ) ? "as {$this->alias}" : '';
		switch ($this->action)
		{
			case 'select' :
				$this->sql = "select $dist $fl from {$this->table} $alias $jn $wh $gp $hv $ob $lm";
				break;
			case 'update' :
				$this->sql = "update {$this->table} $jn $sf $wh";
				break;
			case 'delete' :
				$this->sql = "delete from {$this->table} $wh";
				break;
			case 'insert' :
				$this->sql = "insert into {$this->table} $sf";
				break;
			case 'insert_dup' :
				$this->sql = "insert into {$this->table} $sf $df";
				break;
			case 'new' :
				$this->sql = "insert into {$this->table} $sf";
				break;
			case 'describe' :
				$this->sql = "show columns from {$this->table}";
				break;
			case 'truncate' :
				$this->sql = "truncate table {$this->table}";
				break;
			case 'show' :
				$this->sql = "show FULL columns from {$this->table} $wh";
				break;
//			default : $from = '' ;

		}
	}

	function getRow( $num = 0 , $valueField = '' , $valueData = '' )
	{
		$this->select( $valueField , $valueData );
		return ( $num === 'all' ) ? $this->rows : $this->rows[ $num ];
	}

	function retSQL()
	{
		$this->buildSQL();
		return $this->sql;
	}

	function select( $valueField = '' , $valueData = '' )
	{
		$this->action = 'select';
		return $this->process( $valueField , $valueData );
	}

	function selectAll( $valueField = '' , $valueData = '' )
	{
		$this->clearWhere();
		$this->clearHaving();
		return $this->select( $valueField , $valueData );
	}

	function update( $valueField = '' , $valueData = '' )
	{
		$this->action = 'update';
		return $this->process( $valueField , $valueData );
	}

	function delete()
	{
		$this->action = 'delete';
		return $this->process( '' , '' );
	}

	function insert()
	{
		$this->action = 'insert';
		return $this->process( '' , '' );
	}

	function describe()
	{
		$this->action = 'describe';
		$this->process();
		return $this->Columns;
	}

	function show_columns()
	{
		$this->describe();
		return $this->process();
	}

	public function truncate()
	{
		$this->action = 'truncate';
		return $this->process();
	}

	function showFull()
	{
		$this->action = 'show';
		$this->process();
	}

	/**
	 * Gets the value of a particular field from the result set
	 *
	 * @param $fieldName
	 * @return mixed
	 */
	function getField( $fieldName )
	{
		if ( count( $this->rows) == 1 )
		{
			return $this->rows[0]->{ $fieldName } ;
		}
		elseif( count( $this->rows) > 1 )
		{
			/*
			 * @ToDo Add facility to return either array of values or separated values
			 */
			return false ;
		}
		else
		{
			return false ;
		}
	}

	function getData( \mysqli_result $result )
	{
		while ($row = $result->fetch_object( $this->fetchObj , $this->fetchObjParams ))
		{
			if ( @ $this->rowKeyField )
			{
				$this->rows[ "{$this->rowKeyPrefix}{$row->{$this->rowKeyField}}" ] = $row;
			}
			else
			{
				if ( ( $this->found == 1 || $this->recordLimit == 1 ) && $this->rowObjToThis )
				{
					foreach ( $row as $key => $val )
					{
						$key = str_replace( ' ' , '_' , $key );
						$this->{$key} = $val;
					}
				}
				else
				{
					if ( $this->groupBy && $this->groupBy->rollup )
					{
						$this->groupBy->parse( $row );
					}
					else
					{
						$this->rows[] = $row;
					}
				}
			}
//			if ( @ $valueField ) $this->{$row->{$valueField}} = $row->{$valueData} ;
		}
	}

	function process( $valueField = '' , $valueData = '' )
	{
		global $mysqli;
		$this->buildSQL();
		if ( $mysqli->connect_errno )
		{
			$this->error = 'Failed to connect to MySQL';
			return false;
		}
		if ( $this->forceDbChange )
		{
			$mysqli->select_db( $this->dbDatabase ) ;
		}
		else
		{
			if ( ( $this->dbDatabase && $this->dbDatabase != DB_DATABASE ) ) $mysqli->select_db( $this->dbDatabase ) ;
		}
		$sqlstart = microtime( true );
		$result = $mysqli->query( $this->sql );
		$sqlend = microtime( true );
		$this->sql_query_time = round( $sqlend - $sqlstart , 4 );
		if ( $this->action == 'insert' ) $this->last_insert_id = $mysqli->insert_id;
		$this->error = $mysqli->error;
		$this->affected = $mysqli->affected_rows;
		if ( $result )
		{
			if ( $this->action == 'select' )
			{
				if ( $this->limitOffset )
				{
					$offs = new \mysqli( DB_SERVER , DB_USER , DB_PASS , $this->dbDatabase );
					$jn = $this->joins->build();
					$wh = $this->where->build();
					$hv = $this->having->build();
					$res = $offs->query( "select count(*) as num from {$this->table} $jn $wh $hv" );
					$row = $res->fetch_row();
					$this->found = $row[ 0 ];
				}
				else
				{
					$this->found = $result->num_rows;
				}
				$this->rows = array();
				if ( $this->fetchObj != 'standardSQLrequestObj' && $this->fetchObjParams )
				{
					$this->getData( $result );
				}
				else
				{
					while ($row = $result->fetch_object( $this->fetchObj ))
					{
						if ( @ $this->rowKeyField )
						{
							$this->rows[ "{$this->rowKeyPrefix}{$row->{$this->rowKeyField}}" ] = $row;
						}
						else
						{
							if ( ( $this->found == 1 || $this->recordLimit == 1 ) && $this->rowObjToThis )
							{
								foreach ( $row as $key => $val )
								{
									$key = str_replace( ' ' , '_' , $key );
									$this->{$key} = $val;
								}
							}
							else
							{
								if ( $this->groupBy && $this->groupBy->rollup )
								{
									$this->groupBy->parse( $row );
								}
								else
								{
									$this->rows[] = $row;
								}
							}
						}
						if ( $valueField && $row->{$valueField} ) $this->{$row->{$valueField}} = ( $valueData ) ? $row->{$valueData} : '';
					}
				}
			}
			elseif ( $this->action == 'describe' || $this->action == 'show' )
			{
				$this->Columns = new \stdClass;
				while ($row = $result->fetch_object( $this->fetchObj ))
				{
					if ( $this->action == 'show' && @ $row->Comment )
					{
						$comm = json_decode( $row->Comment );
						if ( json_last_error() )
						{
							$c = new \stdClass;
							$c->comment = $row->Comment;
							$row->Comment = $c;
						}
						else
						{
							$row->Comment = $comm;
						}
					}
					$colKeyPrefix = ( $this->rowKeyPrefix ) ? $this->rowKeyPrefix : '';
					$this->Columns->{$colKeyPrefix . $row->Field} = $row;
				}
			}
//            $result->close() ;
		}
		else
		{
			//	echo 'Error: ' . $this->sql . '  <br>' ;
		}
		return ( $this->error ) ? false : true;
	}

	function retPrimaryKeyField()
	{
//		echo DB_SERVER . '<br>' . DB_USER . '<br>' . DB_PASS . '<br>' . $this->dbDatabase;
		$pkeys = new \MySQLi( DB_SERVER , DB_USER , DB_PASS , ( $this->dbDatabase ) ? $this->dbDatabase : 'allgrads_dbms' );
		if ( $pkeys->connect_errno )
		{
			error_log( 'Failed to connect to MYSQL in ' . __FILE__ . ' at line: ' . __LINE__ );
		}
		else
		{
			if ( $result = $pkeys->query( "select `primary_key_field` from pkeys where `table` = '{$this->table}'" ) )
			{
				if ( $result->num_rows == 1 )
				{
					$row = $result->fetch_object();
					$result->free();
					return $row->primary_key_field;
				}
				else
				{
					$result = $pkeys->query( "show columns from {$this->table} where `KEY`='PRI'" );
					if ( $result->num_rows == 1 )
					{
						$row = $result->fetch_object();
						$result->free();
						$pkeys->query( "insert into pkeys set `table` = '{$this->table}', `primary_key_field` = '{$row->Field}'" );
						return $row->Field;
					}
				}
			}
			else
			{
				error_log( "Using {$this->dbDatabase}" );
				error_log( "Query: select `primary_key_field` from pkeys where `table` = '{$this->table}' in " . __FILE__ . ' at line: ' . __LINE__ );
			}
		}
		return '';
	}

	function csvArray()
	{
		$arr[] = array_keys( (array)$this->rows[ 0 ] );
		foreach ( $this->rows as $row )
		{
			$arr[] = array_values( (array)$row );
		}
//		print_r( $arr ) ; exit ;
		return $arr;
	}

    function query($queryString) {
        global $mysqli;
        $result = $mysqli->query( $queryString );
        return $result;

    }

}
