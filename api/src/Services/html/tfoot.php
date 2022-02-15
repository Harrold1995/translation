<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 19/04/2016
 * Time: 5:05 PM
 */

namespace App\Services\html;


class tfoot extends tag{
	
	use tableElements ;
	
	function __construct( $content = '' )
	{
	    parent::__construct( 'tfoot' , $content ) ;
	}
}