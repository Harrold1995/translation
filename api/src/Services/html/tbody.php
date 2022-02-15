<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 14/04/2016
 * Time: 4:29 PM
 */

namespace App\Services\html;


class tbody extends tag {

	use tableElements ;

	function __construct( $data = '' )
	{
	    parent::__construct( 'tbody' , $data ) ;
	}
}