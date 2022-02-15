<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 4:09 PM
 */

// namespace API\sql;
namespace App\Services\Sql;


class sqlFunctionParam
{
	function __construct( $val ) {
		$this->value = $val ;
	}

	function build() {
		return $this->value ;
	}
}

