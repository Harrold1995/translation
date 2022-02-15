<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/03/15
 * Time: 9:26 AM
 */


class parseClass {
    /**
     * Returns the name of the class without namespacing or if $name_equals is specified boolean if name = val
     * @param      $object
     *
     * @return string
     */
    static function name( $object )
    {
        $cname = ( is_string( $object ) ) ? $object : get_class( $object ) ;
        $arr = explode( '\\' , $cname ) ;
        return array_pop( $arr ) ;
    }

    /**
     * @param $object
     *
     * @return bool|string
     */
    static function name_space( $object )
    {
        $arr = explode( '\\' , get_class( $object ) ) ;
        array_pop( $arr );
        if( $arr )
        {
            $ns = implode( '\\' , $arr ) ;
        }
        else
        {
            $ns = '\\';
        }
        return $ns ;
    }
}