<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 19/04/2016
 * Time: 7:42 PM
 */

namespace App\Services\html;


trait tableElements{

	public function tr( $data , cssStyle $css = null ) {
		$tr = new tr( $data ) ;
		if ( $css ) $tr->addStyle( $css ) ;
		$this->data .= $tr->build() ;
	}

}