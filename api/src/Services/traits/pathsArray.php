<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 27/6/17
 * Time: 2:07 PM
 */

namespace API\traits;


use Slim\Http\Request;

trait pathsArray
{

	private function pathsArray( Request $request , $place , $file_name )
	{
		$path = ( $request->getUri()->getHost() == 'localhost' ) ? '/Applications/MAMP/htdocs/storage/box/' . $place : '/var/box/' . $place . '/toBox';
		$pathsArr[] = $path . '/' . $file_name;
		$pathsArr[] = '/var/box/' . $place . '/inBox/' . $file_name;
		return $pathsArr ;
	}
}