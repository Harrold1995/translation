<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 9:45 AM
 */

namespace App\Services\html;


class div extends standardExtensions{
    use tagtraits ;
    function __construct( $data = '' ) { parent::__construct( 'div' , $data ) ; }
}
