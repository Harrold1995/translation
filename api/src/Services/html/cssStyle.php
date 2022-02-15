<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 10:52 AM
 */

// namespace html;
namespace App\Services\html;


class cssStyle {
    private $type ;
    private $name ;
    private $str ;
    var $font ;
    var $size ;
    var $weight ;
    var $padding ;
    var $bgcolour ;
    var $color ;

    function __construct( $type = 'inline' , $name = '' ) {
        $this->type = $type ;
        $this->name = $name ;
    }

    public function __toString()
	{
		return $this->build() ;
	}

	function addOption( $name , $val ) {
        if ( $name ) $this->{$name} = $val ;
    }

    function add( $name , $val ) {
        $this->addOption( $name , $val ) ;
    }

    function getName() {
        return $this->name ;
    }

    function border( $type , $col = 'black' , $w = '1px' , $style = 'solid' ) {
        if ( $type == 'all' ) {
            $this->add( 'border' , "$w $style $col" ) ;
        } else {
            $this->marge_pad( 'border' , $type , $style , '' , '-style' ) ;
            $this->marge_pad( 'border' , $type , $w , '' , '-width' ) ;
            $this->marge_pad( 'border' , $type , $col , '' , '-color' ) ;
        }
    }

    function font( $font , $size = false , $color = false , $weight_style = '' ) {
        $this->add( 'font-family' , $font ) ;
        if ( $size ) $this->add( 'font-size' , $size ) ;
        if ( strpos( $weight_style , 'bold' ) !== false ) $this->bold() ;
        if ( strpos( $weight_style , 'oblique' ) !== false ) $this->add( 'font-style' , 'oblique' ) ;
        if ( strpos( $weight_style , 'italic' ) !== false ) $this->add( 'font-style' , 'italic' ) ;
        if ( strpos( $weight_style , 'upper' ) !== false ) $this->add( 'text-transform' , 'uppercase' ) ;
        if ( strpos( $weight_style , 'lower' ) !== false ) $this->add( 'text-transform' , 'lowercase' ) ;
        if ( $color ) $this->add( 'color' , $color ) ;
    }

    public function bold()
    {
        $this->add( 'font-weight' , 'bold' ) ;
    }

    function width( $width , $unit = 'px' ) { $this->add( 'width' , $width . $unit ) ; }
    function height( $height , $unit = 'px' ) { $this->add( 'height' , $height . $unit ) ; }
    function align( $align = 'left' ) { $this->add( 'text-align' , $align ) ; }
    function pad( $type , $width , $unit = 'px' ) { $this->marge_pad( 'padding' , $type , $width , $unit ) ; }
    function margin( $type , $width , $unit = 'px' ) { $this->marge_pad( 'margin' , $type , $width , $unit ) ; }
    function visibility( $visibility ) { $this->add( 'visibility' , ( $visibility == 'h' ) ? 'hidden' : ( ( $visibility == 'v' ) ? 'visible' : $visibility ) ) ; }
    function vis( $vis ) { $this->visibility( $vis ) ; }
    function floatLeft() { $this->add( 'float' , 'left' ) ; }
    function floatRight() { $this->add( 'float' , 'right' ) ; }

    function position( $type = 'absolute' , $top = false , $left = false , $bottom = false , $right = false ) {
        $this->add( 'position' , $type ) ;
        if ( $top ) $this->add( 'top' , $top ) ;
        if ( $left ) $this->add( 'left' , $left ) ;
        if ( $bottom ) $this->add( 'bottom' , $bottom ) ;
        if ( $right ) $this->add( 'right' , $right ) ;
    }

    private function marge_pad( $padm , $type , $w , $unit , $addendum = '' ) {
        $type = str_replace( array( 'left' , 'right' , 'top' , 'bottom' , 'all' ) , array( 'l' , 'r' , 't' , 'b' , 'a' ) , strtolower( $type ) ) ;
        if( strpos( $type , 'l' ) !== false ) $this->add( $padm . '-left' . $addendum , $w . $unit ) ;
        if( strpos( $type , 'r' ) !== false ) $this->add( $padm . '-right' . $addendum , $w . $unit ) ;
        if( strpos( $type , 't' ) !== false ) $this->add( $padm . '-top' . $addendum , $w . $unit ) ;
        if( strpos( $type , 'b' ) !== false ) $this->add( $padm . '-bottom' . $addendum , $w . $unit ) ;
        if( strpos( $type , 'a' ) !== false ) $this->add( $padm , $w . $unit ) ;
    }

    function build() {
        $subst = array(	'font'		=>	'font-family' 	,	'size'		=>	'font-size'	,
            'weight'	=>	'font-weight'	,	'bgcolour'	=>	'background-color' ,
            'align'		=>	'text-align'
        ) ;
        $open = " { " ; $close = "} \n" ;
        switch ( $this->type ) {
            case 'tag' : $htm = $this->name ; break ;
            case 'id' : $htm = '#' . $this->name ; break ;
            case 'class' : $htm = '.' . $this->name ; break ;
            case 'inline' : $htm = "style=" ; $open="'"; $close="'"; break ;
            default : $htm = $this->name ;
        }
        $htm .= $open ;
        foreach ( $this as $key => $val ) {
            if ( $key == 'type' || $key == 'name' || $key == 'str' || empty( $val ) ) continue ;
            if ( @ $subst[$key] ) $key = $subst[$key] ;
            $htm .= "$key: $val; " ;
        }
        $htm .= $close ;
        $this->str = $htm ;
        return $htm ;
    }

    function display() {
        echo $this->build() ;
    }
}
