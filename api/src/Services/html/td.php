<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:04 AM
 */

namespace App\Services\html;


class td extends standardExtensions {
    function __construct( $data = '' ) { parent::__construct( 'td' , $data ) ; }
    function colspan( $span = 1 ) { $this->addOption( 'colspan' , $span ) ; }
    function rowspan( $span = 1 ) { $this->addOption( 'rowspan' , $span ) ; }
    function valign( $val ) { $this->addOption( 'valign' , $val ) ; }
    function nowrap() { $this->addOption( 'nowrap' ) ; }
    
    use tagtraits ;
}
