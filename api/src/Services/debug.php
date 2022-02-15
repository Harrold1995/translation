<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 28/05/15
 * Time: 7:12 PM
 */

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
		$tag = '<pre style="' ;
		$tag .= "background-color: #f5f5f5; " ;
		$tag .= "display: block; " ;
		$tag .= "padding: 9.5px; " ;
		$tag .= "margin: 0 0 10px; " ;
		$tag .= "font-size: 13px; " ;
		$tag .= "line-height: 1.42857143; " ;
		$tag .= "color: #333; " ;
		$tag .= "word-break: break-all; " ;
		$tag .= "word-wrap: break-word; " ;
		$tag .= "border: 1px solid #ccc; " ;
		$tag .= "border-radius: 5px; " ;
		$tag .= '">' . print_r( $content , true ) . '</pre>' ;
		echo $tag ;
	}

	public static function in( $code = 'generic' , $office_network_only = false ) {
		if ( ! $code ) return false ;
		$dev = new standardSQLrequest( 'development' ) ;
		$dev->addFieldCalc( 'status' , 'scope + 0' ) ;
		$dev->where( 'code' , $code ) ;
		$dev->recordLimit = 1 ;
		$dev->rowObjToThis = true ;
		$dev->select() ;
		$remaddr = $_SERVER['REMOTE_ADDR'] ;
		if ( @ $dev->status == 3 ) return true ;
		if ( @ $dev->status == 2 ) {
			switch( substr( $remaddr , 0 , 12 ) ) {
				case '192.168.100.' : return true ;
				case '192.168.101.' : return true ;
				default : return ( $remaddr == '60.240.228.200' ) ;
			}
		}
		if( @ $dev->status == 1 ) {
			if( $office_network_only ) return ( $remaddr == '192.168.' . ( ( $office_network_only == 221 ) ? '100.221' : '101.' . $office_network_only ) ) ;
			return	(	$remaddr == '192.168.100.221' ||
				$remaddr == '60.240.228.200' ||
				substr( $remaddr , 0 , 12 ) == '192.168.101.'
			) ;
		}
		return false ;
	}


}
