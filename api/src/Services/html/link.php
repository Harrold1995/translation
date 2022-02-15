<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:01 AM
 */

namespace App\Services\html;


class link extends tag {
    function __construct( $content , $href = '' ) {
        parent::__construct( 'a' , $content ) ;
        if ( $href ) $this->href( $href ) ;
    }
    function href( $href ) { $this->add( 'href' , $href ) ; }
    function target( $target ) { $this->add( 'target' , $target ) ; }
    function title( $title ) { $this->add( 'title' , $title ) ; }
}
