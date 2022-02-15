<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 10:57 AM
 */

namespace App\Services\html;

class textarea extends standardExtensions {
    function __construct( $data = '' ) { parent::__construct( 'textarea' , $data ) ; }
    function cols( $cols ) { $this->add( 'cols' , $cols ) ;	}
    function rows( $rows ) { $this->add( 'rows' , $rows ) ;	}
    function readonly() { $this->add( 'readonly' , 'readonly' ) ; }
}
