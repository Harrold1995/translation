<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 19/04/2016
 * Time: 4:46 PM
 */

namespace App\Services\html;


class button extends tag{

	function __construct( $content = '' , $type = 'button' )
	{
	    parent::__construct( 'button' , $content ) ;
		$this->add( 'type' , $type ) ;
	}
	
	static function html( $content = '' , $class = '' , $type = '' )
	{
		$s = new self( $content , $type ) ;
		if ( $class ) $s->add( 'class' , $class ) ;
		return $s->build() ;
	}
}