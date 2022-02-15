<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 19/4/17
 * Time: 11:47 AM
 */

namespace API\traits;


trait loginObj
{
	private function loginObject( $access , $data = [] )
	{
		$err = ( $this->error ) ? $this->error : ( ( $this->found > 1 ) ? 'Multiple accounts found' : false );
		if ( $this->found < 1 ) $err = 'Invalid login credentials';
		$obj = (object)['error' => $err , 'data' => (object)[]];
		$obj->found = $this->found;
		$obj->data->access = $access ;
		foreach ( $data as $key => $val )
		{
			$obj->data->{$key} = $this->getField( $val ) ;
		}
		return $obj ;
	}
}