<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 22/2/17
 * Time: 11:34 AM
 */

namespace API\traits;

use Slim\Http\Request;

trait getPathfromPaths
{

	private function getPathFromPaths( $paths )
	{
		$imagePath = array_shift( $paths );
		if ( ! $imagePath ) return false ;
		if ( file_exists( $imagePath ) ) return $imagePath;
		return $this->getPathFromPaths( $paths ) ;
	}

	private function pathsArray( Request $request , $place , $file_name , $ext = '' )
	{
		$extension = ( $ext) ? ".$ext" : '' ;
		$path = ( $request->getUri()->getHost() == 'localhost' ) ? '/Applications/MAMP/htdocs/storage/box/' . $place : '/var/box/' . $place . '/toBox';
		$pathsArr[] = $path . '/' . $file_name . $extension;
		$pathsArr[] = '/var/box/' . $place . '/inBox/' . $file_name . $extension ;
		return $pathsArr ;
	}

	private function getFullPath( Request $request , $place , $file_name , $ext = '' )
	{
		$pathsArr = $this->pathsArray( $request , $place , $file_name , $ext );
//		print_r( $pathsArr ); exit;
		return $this->getPathFromPaths( $pathsArr );
	}

}