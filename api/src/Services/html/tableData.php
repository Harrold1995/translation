<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/03/15
 * Time: 10:42 AM
 */

namespace App\Services\html;

use \parseClass ;


class tableData {
    var $headers = array() ;
	/* @var $bodies tableDataBodies */
    var $bodies ;
	/* @var $columns tableDataColumns */
    var $columns ;
	/* @var $rows tableDataRows */
    var $rows ;
    var $wrapHTML = false ;
    var $echoOn = true ;
	/* @var $table table */
    var $table ;
    var $width ;
    var $id ;
    var $emptyCellDefault ;
    var $footer ;
    /* @var $oddEvenClass boolean */
    var $oddEvenClass ;					// Boolean If true we will add an odd and even class to the rows

	function __construct()
	{
		$this->columns = new tableDataColumns() ;
		$this->rows = new tableDataRows ;
		$this->bodies = new tableDataBodies ;
		$this->table = new table ;
//		$this->table = new tag( 'table' , '' ) ;
	}


    function addHeader( $head ) {
        $this->headers[] = new tableDataHeader ( $head ) ;
    }

    function add ( $opt , $val = '' ) {
        $this->table->add( $opt , $val ) ;
    }

    function addBody() {
        $this->bodies->addBody() ;
        $bod = $this->currentBody() ;
        $bod->oddEvenClass = $this->oddEvenClass ;
        return $bod ;
    }

	/**
	 * @return tableDataRow
	 */
	function addRow() {
        if ( count ( $this->bodies->data ) < 1 ) $this->addBody() ;
		/* @var $body tableDataBody */
        $body = $this->currentBody() ;
        $body->addRow() ;
        return $body->currentRow() ;
    }

    function rowAddColumn( $col , $val ){
        $this->columns->addColumn( $col ) ;
        return $this->bodies->addColumn ( $col , $val ) ;
    }

    function coliumns() {
        return $this->columns->columnCount ;
    }

	/**
	 * @return mixed
	 */
	function currentBody() {
        return $this->bodies->data[ count ( $this->bodies->data ) - 1 ] ;
    }

    function currentRow(){
        return $this->rows->data[ count ( $this->rows->data ) - 1 ] ;
//        $body = $this->currentBody() ;
//        return $body->currentRow() ;
    }

    function addStyle( $cssStyleClassObj ) {
        $this->table->style = $cssStyleClassObj ;
    }

    function addOption( $name , $val ) {
        $this->table->addOption ( $name , $val ) ;
    }

    function moveColumn ( $id , $pos = 'last' ) {
        if( $this->columns->{$id} ) {
            unset( $this->columns->{$id} ) ;
            $this->columns->addColumn( $id ) ;
        }
    }

    function display( $id = '' ) {
        if ( $id ) {
            $this->id = $id ;
            $this->table->addOption( 'id' , $id ) ;
        }
        if ( $this->width ) $this->table->addOption( "width" , $this->width ) ;
        $this->table->data = $this->columns->display() ;
//        echo "<pre>" . print_r( $this->columns , true ) . "</pre>" ; exit ;
        if ( $this->footer ) {
            $tf = new tag( 'tfoot' , $this->footer ) ;
            $this->table->data .= $tf->build() ;
        }
        $this->table->data .= $this->bodies->display( $this->columns ) ;
        $htm = '' ;
		foreach ( $this->headers as $key => $header ) $htm .= $header->display() ;
        $htm .= $this->table->build() ;
        if ( $this->echoOn ) {
            echo $htm ;
        } else {
            return $this->wrapBody ( $htm ) ;
        }
    }

    function csv( $line_ending = "\n" ) {
        return $this->columns->csv() . $line_ending . $this->bodies->csv( $this->columns , $line_ending ) ;
    }

    function build( $id = '' ) {
        $this->echoOn = false ;
        return $this->display( $id ) ;
    }

    function wrapBody( $htm ) {
        if ( $this->wrapHTML ) {
            return "<html><head></head><body>$htm</body></html>" ;
        } else {
            return $htm ;
        }
    }

    function cellspacing( $space ) { $this->table->add( 'cellspacing' , $space ) ; }
    function cellpadding( $pad ) { $this->table->add( 'cellpadding' , $pad ) ; }
    function width( $w ) { $this->table->add( 'width' , $w ) ; }
    function height( $h ) { $this->table->add( 'height' , $h ) ; }
}

class tableDataHeader {
    var $data ;

	function __construct( $data )
	{
		$this->data = $data ;
	}

    function display( $tag = 'p' ) {
        return "<$tag>{$this->data}</$tag>" ;
    }

}

class tableDataBodies {
    var $data = array() ;
    var $oddEvenClass ;

	/**
	 * @return tableDataBody
	 */
	function addBody() {
        $this->data[] = new tableDataBody ;
        $bod = $this->data[ count( $this->data ) - 1 ] ;
        $bod->oddEvenClass = $this->oddEvenClass ;
        return $bod ;
    }

    function current() {
        if ( count( $this->data < 1 ) ) $this->data[] = new tableDataBody ;
        return $this->data[ count( $this->data ) - 1 ] ;
    }

    function addColumn( $col , $val ) {
        return $this->data[ count( $this->data ) - 1 ]->addColumn( $col , $val )  ;
    }

    function display( $cols ) {
        $htm = '' ;
//		echo "<pre>" . print_r( $this->data , true ) . "</pre>" ; exit ;
        foreach ( $this->data as $key => $bod ) {
			/* @var $bod tableDataBody */
//			echo "<pre>" . print_r( $bod , true ) . "</pre>" ; exit ;
            $htm .= $bod->build( $cols ) ;
        }
        return $htm ;
    }

    function csv( $cols , $line_ending = "\n" ) {
        //	if ( inDebug() ) { print_r( $this ) ; die() ; }
        foreach ( $this->data as $key => $d ) {
            @ $arr[] = $d->rows->csv( $cols , $line_ending ) ;
        }
        return implode( $line_ending , $arr ) ;
    }

}

class tableDataBody {
    var $tbody ;
	/* @var $rows tableDataRows */
    var $rows ;
    var $oddEvenClass ;

	function __construct()
	{
		$this->tbody = new tag( 'tbody' ) ;
		$this->rows = new tableDataRows ;
	}

    function addRow() {
        $this->rows->addRow() ;
        $row = $this->currentRow() ;
        if ( $this->oddEvenClass ) $row->add( 'class' , ( count ( $this->rows->data ) % 2 ) ? 'even' : 'odd' ) ;
        return $row ;
    }

	/**
	 * @return mixed
	 */
	function currentRow(){
        return $this->rows->data[ count ( $this->rows->data ) - 1 ] ;
    }

    function addColumn( $col , $val ) {
        return $this->rows->addColumn( $col , $val ) ;
    }

    function build( $cols ) {
        $this->tbody->data = $this->rows->display( $cols ) ;
        return $this->tbody->build() ;
    }

    function title( $title ) {
        $this->tbody->add( 'title' , $title ) ;
    }

    function clss( $clss ) {
        $this->tbody->add( 'class' , $clss ) ;
    }

    function add( $name , $data ) {
        $this->tbody->add( $name , $data ) ;
    }
}

class tableDataRows {
    var $data = array() ;

    function addRow(){
        $this->data[] = new tableDataRow() ;
    }

    function addColumn( $col , $val ){
        $r = $this->data[ count($this->data) - 1 ] ;
        return $this->data[ count($this->data) - 1 ]->addColumn( $col , $val ) ;
    }

    function csv( $cols , $line_ending = "\n" ){
        $data = array() ;
        foreach ( $this->data as $key => $row ) {
            $data[] = $row->csv( $cols ) ;
        }
        return join( $line_ending , $data ) ;
    }

    function display( $cols ) {
        $htm = '' ;
        foreach ( $this->data as $key => $row ) {
			/* @var $row tableDataRow */
            $htm .= $row->display( $cols ) ;
        }
        return $htm ;
    }
}

class tableDataRow {
    var $tr ;
    var $defaultStyle ;

	function __construct()
	{
		$this->tr = new tag( 'tr' ) ;
	}

    function addColumn ( $col , $val ) {
        if ( $col ) {
            $this->{$col} = new tableRowColumn ($val) ;
            return $this->{$col} ;
        } else {
            return  'Error' ;
        }
    }

    function addOption( $name , $val ) {
        $this->tr->addOption( $name , $val ) ;
    }

    function add( $name , $val = '' ) {
        $this->addOption( $name , $val ) ;
    }

    function addStyle( $cssStyleClassObj ) {
        $this->tr->style = $cssStyleClassObj ;
    }

    function csv( $cols ) {
        $c = 'tableRowColumn' ; $cc = 'tableDataColumn' ;
        $data = array() ;
        foreach ( $cols as $key => $col ) {
            if ( $col instanceof $cc ) {
                if ( @ $this->{$key} ) {
                    if ( $this->{$key} instanceof $c ) $data[] = '"' . str_replace( array( '"' , "\n" ) , array( '""', ' ' ) , $this->{$key}->td->data ) . '"' ;
                } else {
                    $data[] = '""' ;
                }
            }
        }
        return join( ',' , $data ) ;
    }

    function display( $cols ) {
        $s = 'html\cssStyle' ;
        $this->tr->data = '' ;
        foreach ( $cols as $key => $col ) {
            if ( $key != 'columnCount' ) {
                if ( @ $this->{$key} ) {
                    if ( !( @ $this->{$key}->td->style instanceof $s ) && $this->defaultStyle instanceof $s ) $this->{$key}->td->style = $this->defaultStyle ;
                    if ( parseClass::name( $this->{$key} ) == 'tableRowColumn' ) $this->tr->data .= $this->{$key}->display() ;
                } else {
                    if ( $key != 'thead' ) {
                        $td = new tag( 'td' , '&nbsp;' ) ;
                        if ( $this->defaultStyle instanceof $s ) $td->addStyle( $this->defaultStyle ) ;
                        $this->tr->data .= $td->build() ;
                    }
                }
            }
        }
        return $this->tr->build() ;
    }
}

class tableRowColumn {
    var $value ;
    var $td ;
    var $omit_column ;

	function __construct( $val )
	{
		$this->value = $val ;
		$this->td = new td( $val ) ;
	}

    function addOption( $name , $val ) { $this->td->addOption( $name , $val ) ; }
    function add( $name , $val = '' ) { $this->td->addOption( $name , $val ) ; }
    function align( $algn ) { $this->td->align( $algn ) ; }
    function valign( $algn ) { $this->td->valign( $algn ) ; }
    function addStyle( $cssStyleClassObj ) { $this->td->style = $cssStyleClassObj ; }
    function omit(){ $this->omit_column = true; }

    function display() {
        return ( $this->omit_column ) ? '' : $this->td->build() ;
    }
}

class tableDataColumns {
    var $tr ;
    var $thead ;
    var $columnCount ;

	function __construct()
	{
		$this->tr = new tag( 'tr' ) ;
		$this->thead = new tag( 'thead' ) ;
	}

    function addColumn ( $col ) {
        if ( $col ) {
            $this->{$col} = new tableDataColumn( $col ) ;
            $this->columnCount++ ;
        }
    }

    function addOption( $name , $val ) {
        $this->tr->addOption( $name , $val ) ;
    }

    function add( $name , $val = '' ) {
        $this->tr->addOption( $name , $val ) ;
    }

    function addStyle( $style ) {
        $this->tr->addStyle ( $style ) ;
    }

    function display() {
        $this->tr->data = $this->content() ;
        $this->thead->data = $this->tr->build() ;
        return $this->thead->build() ;
    }

    function csv() {
        $data = array() ;
        $class = 'tableDataColumn' ;
        foreach ( $this as $key => $val ) {
            if ( $this->{$key} instanceof $class ) $data[] = $this->{$key}->csv() ;
        }
        return join( ',' , $data ) ;
    }

    function content() {
        $htm = '' ;
        $class = 'html\tableDataColumn' ;
        foreach ( $this as $key => $val ) {
            if ( $this->{$key} instanceof $class ) $htm .= $this->{$key}->display() ;
        }
        return $htm ;
    }
}

class tableDataColumn {
	/* @var $th \html\tag */
    var $th ;

	function __construct( $val )
	{
		$this->th = new tag( 'th' , $val ) ;
	}

    function add( $name , $val = '' ) {
        $this->th->addOption( $name , $val ) ;
    }

    function align( $algn ) {
        $this->th->addOption( 'align' , $algn ) ;
    }

    function addStyle( $cssStyleClassObj ) {
        $this->th->style = $cssStyleClassObj ;
    }

    function csv() {
        return $this->th->data ;
    }

    function display() {
        return $this->th->build() ;
    }
}
