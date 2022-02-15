<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:00 AM
 */

namespace App\Services\html;


class ul extends tag {
    var $items = array() ;
    var $add_css_to_li ;
    function __construct( $add_css_to_li = false ){
        parent::__construct( 'ul' ) ;
        $this->add_css_to_li = $add_css_to_li ;
    }

    function li( $content , $class = '' ) {
        $li = new tag( 'li' , $content ) ;
        if ( $class ) $li->add( 'class' , $class ) ;
        $this->items[] = $li ;
    }

    function display( $echo = true , $debug = false ) {
        $this->data = '' ;
        foreach( $this->items as $key => $li ) {
            if ( $this->add_css_to_li ) $li->addStyle( $this->style ) ;
            $this->data .= $li->build() ;
        }
        return parent::display( $echo , $debug ) ;
    }

    function build() {
        return $this->display( false ) ;
    }
}
