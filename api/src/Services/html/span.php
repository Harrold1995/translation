<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:05 AM
 */

namespace App\Services\html;


class span extends standardExtensions{
    use tagtraits ;
    function __construct( $data = '' ) { parent::__construct( 'span' , $data ) ; }
}
