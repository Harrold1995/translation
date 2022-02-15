<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;


/**
 * @property  fm_interpreter_id
 */
class interpobj
{

	private $fm_interpreter_id;

	function __construct()
	{
		if ( $this->fm_interpreter_id )
		{
			$fmid = $this->fm_interpreter_id;
			unset( $this->fm_interpreter_id );
			$this->languages = languages::get( $fmid );
		}

	}
}