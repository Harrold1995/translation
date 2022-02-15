<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 3/10/18
 * Time: 3:34 PM
 */

namespace API\sql\traits;



use API\functions\args;
use API\functions\argsObj;

trait processOptions
{

    private function processOptions( $options , array $default = null ) : argsObj
    {
//        echo '<pre>' . print_r( $default , true ) . '</pre>';
//        echo '<pre>' . print_r( $options , true ) . '</pre>'; exit ;
        if ( $default ) $options = array_merge( $default , $options );
//        echo '<pre>' . print_r( $options , true ) . '</pre>'; exit;
        $args = args::parse( ( @ $options[0] ) ? $options : [ $options ] );
//        echo '<pre>' . print_r( $args , true ) . '</pre>'; exit;
        if ( $fobj = $args->get( 'fetch' ) ) {
            if ( class_exists( $fobj ) )
            {
                $this->{'fetchObj'} = $fobj;
            }
        }
        if ( $fields = $args->get( 'fields' ) )
        {
            /* @var $this \API\sql\request */
            $this->addFieldArray( $fields );
        }
        if ( $calls = $args->get( 'calls' ) )
        {
//            echo '<pre>' . print_r( $calls , true ) . '</pre>'; exit;
            foreach ( $calls as $call )
            {
                if ( is_array( $call ) )
                {
                    $method = array_shift( $call );
                    if ( method_exists( $this , $method ) )
                    {
                        $this->{ $method }( ...$call );
                    }
                }
                else
                {
                    if ( method_exists( $this , $call ) )
                    {
                        $this->{$call}();
                    }
                }
            }
        }

        return $args ;

    }

}