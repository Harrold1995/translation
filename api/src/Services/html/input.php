<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 10:45 AM
 */

namespace App\Services\html;


class input extends tag {
    var $label ;
    function __construct( $name = false , $val = false ) {
        parent::__construct( 'input' ) ;
        $this->type( 'text' ) ;
        if ( $name ) $this->name( $name ) ;
        if ( $val ) $this->value( $val ) ;
    }

    function type( $t ) { $this->add( 'type' , $t ) ; }
    function value( $v ) { $this->add( 'value' , $v ) ; }
    function checked() { $this->add( 'checked' , 'checked' ) ; }
    function uncheck() { $this->remove( 'checked' ) ; }
    function size( $size ) { $this->add( 'size' , $size ) ; }
    function maxlength( $maxl ) { $this->add( 'MAXLENGTH' , $maxl ) ; }
    function disabled() { $this->add( 'disabled' , 'disabled' ) ; }
    function label( $displayText ) { $this->label = $displayText; }
    function test(){
        return parent::build() ;
    }
    function display( $echo = true , $debug = false ) {
        if( $this->label ) {
            $lab = new tag( 'label' , $this->label ) ; $lab->add( 'for' , $this->get('name') ) ;
            //	$b = parent::display( false ) ;
            //	print_r( $b ) ; exit ;
            $ret = $lab->build() . parent::display( false ) ;
            //	return parent::build() ;
        } else {
            $ret = parent::display( false ) ;
        }
        if( $echo ) {
            echo $ret ;
        } else {
            return $ret ;
        }
    }

    function build() {
        return $this->display( false ) ;
    }
}
