<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:03 AM
 */

namespace App\Services\html;

class tr extends standardExtensions {
    function __construct( $data = '' ) { parent::__construct( 'tr' , $data ) ; }

	/**
     * @param string     $val
     * @param bool|false $css
     * @param bool|false $class
     * @param array      $opts
     */
    function td( $val = '' , $css = false , $class = false , $opts = array() ) {
        $this->tdth( 'td' , $val , $css , $class , $opts ) ;
    }

    function th( $val = '' , $css = false , $class = false , $opts = array() )
    {
        $this->tdth( 'th' , $val , $css , $class , $opts ) ;
    }

    private function tdth( $tag ,  $val = '' , $css = false , $class = false , $opts = array() )
    {
        $td = new tag( $tag , $val ) ;
        if ( $css ) {
			if ( is_array( $css ) )
			{
				foreach ( $css as $item )
				{
					$td->addStyle( $item ) ;
				}
			}
			else
			{
				$td->addStyle( $css ) ;
			}
		}
        if ( $class ) $td->add( 'class' , $class ) ;
        foreach ( $opts as $key => $opt ) {
            $td->add( $key , $opt ) ;
        }
        $this->data .= $td->build() ;
    }

	use tagtraits ;

}
