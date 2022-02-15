<?php


/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 28/05/15
 * Time: 7:12 PM
 */

// namespace API\functions;
namespace App\Services\Functions;


use API\html\cssStyle;
use API\html\tag;

class debug {

    public static function saveInfo( $file_name , $txt , $append = false ) {
        file_put_contents( FULL_PATH . ".debug/" . $file_name, $txt , ( $append ) ? FILE_APPEND : false ) ;
    }

    public static function add( $file_name , $txt )
    {
        self::saveInfo( $file_name , $txt , true ) ;
    }

    /**
     * @param $obj
     */
    public static function log( $obj )
    {
        $arr = explode( "\n" , print_r( $obj , true ) ) ;
        foreach ( $arr as $line ) {
            error_log( $line ) ;
        }

    }

	public static function pre( $content )
	{
		$css = new cssStyle();
		$css->add( 'background-color' , '#f5f5f5' ) ;
		$css->add( 'display','block' ) ;
		$css->add( 'padding','9.5px' ) ;
		$css->add( 'margin','0 0 10px' ) ;
		$css->add( 'font-size','13px' ) ;
		$css->add( 'line-height','1.42857143' ) ;
		$css->add( 'color','#333' ) ;
		$css->add( 'word-break','break-all' ) ;
		$css->add( 'word-wrap','break-word' ) ;
		$css->add( 'border','1px solid #ccc' ) ;
		$css->add( 'border-radius','5px' ) ;
		$css->font( '"Lucida Console", Monaco, monospace') ;
		$tag = new tag('pre' , print_r( $content , true ) ) ;
		$tag->addStyle( $css ) ;
		echo $tag ;
    }

//    public static function in( $code = 'generic' , $office_network_only = false ) {
//        if ( ! $code ) return false ;
//        $dev = new standardSQLrequest( 'development' ) ;
//        $dev->addFieldCalc( 'status' , 'scope + 0' ) ;
//        $dev->where( 'code' , $code ) ;
//        $dev->recordLimit = 1 ;
//        $dev->rowObjToThis = true ;
//        $dev->select() ;
//        $remaddr = $_SERVER['REMOTE_ADDR'] ;
//        if ( @ $dev->status == 3 ) return true ;
//        if ( @ $dev->status == 2 ) {
//            switch( substr( $remaddr , 0 , 12 ) ) {
//                case '192.168.100.' : return true ;
//                case '192.168.101.' : return true ;
//                default : return ( $remaddr == '60.240.228.200' ) ;
//            }
//        }
//        if( @ $dev->status == 1 ) {
//			if( $office_network_only ) return ( $remaddr == '192.168.' . ( ( $office_network_only == 221 ) ? '100.221' : '101.' . $office_network_only ) ) ;
//            return	(	$remaddr == '192.168.100.221' ||
//                $remaddr == '60.240.228.200' ||
//                substr( $remaddr , 0 , 12 ) == '192.168.101.'
//            ) ;
//        }
//        return false ;
//    }


}