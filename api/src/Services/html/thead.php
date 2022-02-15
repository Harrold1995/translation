<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 14/04/2016
 * Time: 4:09 PM
 */

namespace App\Services\html;


class thead extends tag {
	
	use tableElements ;
	
	function __construct( $data = '' )
	{
	    parent::__construct( 'thead' , $data ) ;
	}
}