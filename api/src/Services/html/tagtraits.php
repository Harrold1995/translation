<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 3/05/2016
 * Time: 2:56 PM
 */

namespace App\Services\html;


trait tagtraits {

	public static function html( $data = '' , cssStyle $css = null , ...$options )
	{
		/* @var $s \html\tag */
		$args = \args::parse( $options ) ;
		$s = self::instance( \parseClass::name( get_called_class() ) , $data ) ;
		if ( $css ) $s->addStyle( $css ) ;
		foreach ( $args as $key => $val ) 
		{
			$s->add( $key , $val ) ;
		}
		return $s->build() ;
	}

}