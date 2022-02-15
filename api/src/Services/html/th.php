<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 11:05 AM
 */

namespace App\Services\html;


class th extends td{
    
    use tagtraits ;
    
    function __construct( $data = '' )
    {
        parent::__construct( $data ) ;
        $this->tag = 'th' ;
    }


}
