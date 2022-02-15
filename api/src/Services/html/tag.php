<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 26/02/15
 * Time: 1:18 PM
 */

// namespace html;
namespace App\Services\html;

// use App\Services\html\cssStyle;
use App\Services\Functions\parseClass;

function tag( $tag, $data, $options = array() )
{
	$opts = array();
	foreach ( $options as $key => $val )
	{
		$opts[ $key ] = "$key='$val'";
	}
	$opts = ( $opts ) ? ' ' . join( ' ', $opts ) : '';
	return "<$tag" . $opts . ">$data</$tag>";
}

class tag {
	public $tag;
	public $data;
	public $opts = array();
	public $close = true;
	public $style;

	function __construct( $tag, $data = '' )
	{
		$this->style = new cssStyle;
		$this->tag = $tag;
		$this->data = '' . $data;
		switch ( strtolower( $tag ) )
		{
			case 'input' :
				$this->close = false;
				break;
			case 'img' :
				$this->close = false;
				break;
			case 'link' :
				$this->close = false;
				break;
			case 'col' :
				$this->close = false;
				break;
			case 'hr' :
				$this->close = false;
				break;
		}
	}

	public static function instance( $tag, $data = '' )
	{
		$me = new self( $tag, $data );
		return $me;
	}

	public function set( $value )
	{
		$this->data = $value;
	}

	public function addContent( $content )
	{
		$this->data .= $content;
	}

	function addOption( $name, $data = '' )
	{
		$data = str_replace( "'", '"', $data );
		if ( $data === '' )
		{
			$this->opts[ $name ] = $name;
		}
		else
		{
			$this->opts[ $name ] = "$name='$data'";
		}
	}

	function get( $option )
	{
		return substr( str_replace( $option . "='", '', $this->opts[ $option ] ), 0, -1 );
	}

	function remove( $name )
	{
		unset( $this->opts[ $name ] );
	}

	function add( $name, $data = '' )
	{
		$this->addOption( $name, $data );
		return $this;
	}

	function addStyle( cssStyle $cssStyleClassObj )
	{
		if ( \parseClass::name( $this->style ) != 'cssStyle' ) $this->style = new cssStyle();
		foreach ( $cssStyleClassObj as $key => $opt )
		{
			if ( $opt )
			{
				$this->style->add( $key, $opt );
			}
		}
	}

	function align( $algn )
	{
		switch ( $algn )
		{
			case 'r' :
				$algn = 'right';
				break;
			case 'l' :
				$algn = 'left';
				break;
			case 'c' :
				$algn = 'center';
				break;
		}
		$this->addOption( 'align', $algn );
	}

	function id( $id ) { $this->addOption( 'id', $id ); }

	function click( $func ) { $this->addOption( 'onclick', $func ); }

	function change( $func ) { $this->add( 'onchange', $func ); }

	function mouseover( $func ) { $this->add( 'onMouseOver', $func ); }

	function mouseout( $func ) { $this->add( 'onMouseOut', $func ); }

	function clss( $class ) { $this->addOption( 'class', $class ); }

	function name( $name ) { $this->addOption( 'name', $name ); }

	function display( $echo = true )
	{
		if ( parseClass::name( $this->style ) == 'cssStyle' )
		{
			if ( ( $style = $this->style->build() ) != "style=''" ) $this->opts[ 'style' ] = $style;
		}
		$opts = ( $this->opts ) ? ' ' . join( ' ', $this->opts ) : '';
		if ( $this->close )
		{
			$bld = "<{$this->tag}" . $opts . ">{$this->data}</{$this->tag}>";
		}
		else
		{
			$bld = "<{$this->tag}" . $opts . ">";
		}
		if ( $echo )
		{
			echo $bld;
			return '';
		}
		else
		{
			return $bld;
		}
	}

	function build()
	{
		return $this->display( false );
	}

	public function __toString()
	{
		return $this->build();
	}
}
