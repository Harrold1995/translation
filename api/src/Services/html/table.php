<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:02 AM
 */

namespace App\Services\html;


class table extends standardExtensions {
    function __construct( $data = '' ) { parent::__construct( 'table' , $data ) ; }
    function cellpadding( $pad ) { $this->add( 'cellpadding' , $pad ) ; }
    function cellspacing( $space ) { $this->add( 'cellspacing' , $space ) ; }
    function border( $border ) { $this->add( 'border' , $border ) ; }
    function tr( $data , $css = false ) {
        $tr = new tr( $data ) ;
        if ( $css ) $tr->addStyle( $css ) ;
        $this->data .= $tr->build() ;
    }

    function set_defaults() {
        $this->cellspacing( '0' ) ; $this->cellpadding( 5 ) ; $this->width( '100%' ) ;
    }
}
