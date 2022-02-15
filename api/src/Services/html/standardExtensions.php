<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 9:46 AM
 */

namespace App\Services\html;

class standardExtensions extends tag {
    function height( $h ) { $this->addOption( 'height' , $h ) ; }
    function width ( $w , $unit = '' ) { $this->addOption( 'width' , $w . $unit ) ; }
    function bgcolor( $color ) { $this->add( 'bgcolor' , $color ) ; }


}
