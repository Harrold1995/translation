<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/03/15
 * Time: 9:36 AM
 */

namespace App\Services\html;


class select extends tag {
	var $options;            //	Array
	var $selected;

	function __construct( $name = false )
	{
		parent::__construct( 'select' );
		if ( $name ) $this->add( 'name', $name );
	}

	function option( $val, $disp = false )
	{
		if ( !$disp ) $disp = $val;
		$opt = new tag( 'option', $disp );
		$opt->add( 'value', $val );
		$opt->{'value'} = $val;
		if ( $this->selected && $val == $this->selected ) $opt->add( 'selected', 'selected' );
		$this->options[ $disp ] = $opt;
	}

	function sortOpts()
	{
		ksort( $this->options );
	}

	function select( $val )
	{
		foreach ( $this->options as $opt )
		{
			/* @var $opt \html\tag */
			if ( $opt->{'value'} == $val )
			{
				$opt->add( 'selected', 'selected' );
			}
			else
			{
				$opt->remove( 'selected' );
			}
		}
	}

	function value( $value )
	{
		foreach ( $this->options as $opt )
		{
			/* @var $opt \html\tag */
			if ( $opt->{'value'} == $value ) $opt->add( 'selected' );
		}
	}

	function display( $echo = true, $debug = false )
	{
		$this->data = '';
		if ( @ $this->options )
		{
			foreach ( $this->options as $opt )
			{
				/* @var $opt \html\tag */
				$this->data .= $opt->build();
			}
		}
		return parent::display( $echo );
	}

	function build()
	{
		return $this->display( false );
	}

}
