<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 10:44 AM
 */

namespace App\Services\html;


class radio {
    var $name ;
    var $labels = array() ;
    var $values = array() ;
    var $container ;
    var $currentValue ;
    var $containerUseClass ;
    function __construct( $name , $container = '' , $labels = false , $values = false ) {
        $this->name = $name ;
        //	$this->container = $container ;
        $this->container = new tag( $container ) ;
        $this->values = ( $values ) ? $values : $labels ;
        $this->labels = $labels ;
    }

    function build(){
        $inp = new input; $inp->type( 'radio' ) ; $inp->name( $this->name ) ;
        $html = '' ;
        foreach ( $this->values as $key => $val ) {
            $inp->uncheck() ;
            $inp->value( $val ) ; $inp->id( $this->name . $key ) ;
            if ( $this->currentValue == $val ) $inp->checked() ;
            if ( $this->labels[$key] ) {
                $lab = new tag( 'label' , $this->labels[$key] ) ; $lab->add( 'for' , $this->name . $key ) ;
            }
            //print_r( $inp ) ;
            $html .= $inp->build() . ( ( @ $lab ) ? $lab->build() : '' ) ;
        }
        if ( $this->container ) {
            $this->container->data = $html ;
            //	$cont = new htmlTag( $this->container , $html ) ;
            if( $this->containerUseClass ) {
                $this->container->add( 'class' , $this->name ) ;
            } else {
                $this->container->id( $this->name ) ;
            }
            return $this->container->build() ;
        } else {
            return $html ;
        }
    }

    function display(){
        echo $this->build() ;
    }
}
