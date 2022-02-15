<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 20/2/17
 * Time: 3:09 PM
 */

namespace API\functions;


class utility
{

	public static function formatCurrency( $num )
	{
		setlocale( LC_MONETARY , 'en_AU' );
		return money_format( '%.2n' , $num );

	}

	public static function convert_smart_quotes( $string )
	{
		$search = array(chr( 145 ) ,
			chr( 146 ) ,
			chr( 147 ) ,
			chr( 148 ) ,
			chr( 151 ));

		$replace = array(
			"'" ,
			"'" ,
			'"' ,
			'"' ,
			'-'
		);

		return str_replace( $search , $replace , $string );
	}

	public static function abnFormat( $abn )
	{
		if ( strlen( $abn ) == 11 )
		{
			return substr( $abn , 0 , 2 ) . ' ' . substr( $abn , 2 , 3 ) . ' ' . substr( $abn , 5 , 3 ) . ' ' . substr( $abn , 8 );
		}
		else
		{
			return $abn;
		}
	}

	/**
	 * @param $mime_type
	 * @return bool|mixed
	 */
	public static function getExtension( $mime_type )
	{
		$extensions = array(
			'image/jpeg' => 'jpg' ,
			'image/pjpeg' => 'jpg' ,
			'image/gif' => 'gif' ,
			'image/tiff' => 'tiff' ,
			'image/png' => 'png' ,
			'text/xml' => 'xml',
			'application/octet-stream' => 'jpg' // Customised for the working with children check as only image is possible to upload
		);

		// Add as many other Mime Types / File Extensions as you like

		return ( @ $extensions[ $mime_type ] ) ? $extensions[ $mime_type ] : false;

	}

	public static function fixArrayKey( &$arr )
	{
		$arr = array_combine( array_map( function ( $str )
		{
			return str_replace( " " , "_" , $str );
		} , array_keys( $arr ) ) , array_values( $arr ) );
//		foreach ( $arr as $key => $val )
//		{
//			if ( is_array( $val ) ) fixArrayKey( $arr[ $key ] );
//		}
	}


}
