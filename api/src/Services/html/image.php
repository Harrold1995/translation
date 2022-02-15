<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 10:58 AM
 */

namespace App\Services\html;


class image extends tag {
    function __construct( $src = '' ) { parent::__construct( 'img' ) ; if($src) $this->source( $src ) ; }
    function source( $src ) { $this->addOption( 'src' , $src ) ; }
    function height( $h ) { $this->addOption( 'height' , $h ) ; }
    function width( $w ) { $this->addOption( 'width' , $w ) ; }
    function noBorder() { $this->add( 'border' , '0' ) ; }
    function border( $border ) { $this->add( 'border' , $border ) ; }
    function alt( $txt ) { $this->add( 'alt' , $txt ) ; }

    static function html( $src = '' , $w = 0 , $h = 0 , $border = 0 )
    {
        $s = new self( $src ) ;
        if ( $w ) $s->width( $w ) ;
        if ( $h ) $s->height( $h ) ;
        if ( $border > 0 ) $s->border( $border ) ;
        if ( $border < 0 ) $s->noBorder() ;
		return $s->build() ;
    }
}
