<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:06 AM
 */

namespace App\Services\html;

class iframe extends standardExtensions {
    function __construct( $data = '' ) { parent::__construct( 'iframe' , $data ) ; }
    function frameborder( $border = '0' ) { $this->add( 'frameborder' , $border ) ; }
    function scrolling( $scroll = 'no' ) { $this->add( 'scrolling' , $scroll ) ; }
    function marginheight( $marginheight = '0' ) { $this->add( 'marginheight' , $marginheight ) ; }
    function marginwidth( $marginwidth = '0' ) { $this->add( 'marginwidth' , $marginwidth ) ; }
    function src( $src ) { $this->add( 'src' , $src ) ; }
}
