<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 20/2/17
 * Time: 4:30 PM
 */

namespace API\stationery;

use API\functions\debug;
use API\html\cssStyle;
use API\html\div;
use API\html\image;
use API\html\table;
use API\html\td;
use API\html\tr;
use API\sql\request as standardSQLrequest ;
use API\functions\utility ;
use Mpdf\Mpdf;

class company extends standardSQLrequest
{
	public $company_name;
	public $logo_file;
	public $header_logo_width;
	public $header_logo_height;
	public $abn;
	public $form_header_file;
	public $ABN;

	function __construct( $id ) {
		parent::__construct( 'companies' ) ;
		$this->dataBase( 'allgrads_dbms' ) ;
		$this->forceDbChange = true ;
		$this->where( 'company_id' , $id ) ;
		$this->rowObjToThis = true ;
		$jn = $this->leftJoin( 'bank_accounts' , 'company_id' ) ;
		$jn->multiple() ; $jn->addHard( 'used_for' , 1 ) ;
		$this->process() ;
		//	print_r( $this ) ; die() ;
		$this->footer_function = str_replace( ' ' , '_' , strtolower( $this->company_name ) ) . '_footer' ;
		$this->letterhead_function = str_replace( ' ' , '_' , strtolower( $this->company_name ) ) . '_letterhead' ;
//		debug::pre( $this ) ; exit ;
	}

	/**
	 * @param string $type
	 * @param Mpdf   $pdf
	 *
	 * @return string
	 */
	function statement_header( $type = 'statement' , Mpdf $pdf )
	{
//			print_r( $pdf ) ; die() ;
		$css = new cssStyle; $css->add('font' , 'Arial' ) ; $css->add('font-size' , '9pt' ) ;
		$bcss = new cssStyle; $bcss->add( 'height' , '22mm' ) ;
		$statecss = new cssStyle; $statecss->add( 'font-size' , '24pt' ) ; $statecss->add( 'font-weight' , 'bold' ) ; $statecss->add( 'margin-top' , '10pt' ) ;
		$statecss->align( 'right' ) ;
		if ( ! $this->logo_file ) {
//			debug::pre( $_SERVER ) ;
			$css = new cssStyle; $css->pad( 'left' , 3 , 'mm' ) ;
			$ag = new image( "img/aglogo.png" ) ; $ag->height( '8mm' ) ;
			$il = new image( "img/il_logo.png" ) ; $il->width( '35mm' ) ; $il->addStyle( $css ) ;
			$mx = new image( "img/meanlogo.png" ) ; $mx->width( '35mm' ) ; $mx->addStyle( $css ) ;
			$img = new td( $ag->build() . $il->build() . $mx->build() ) ; $img->align( 'l' ) ; $img->valign( 'top' ) ; $img->width( '50%' ) ;
			$abn = new td ;
		} else {
			$img = new image; $img->source( 'img/' . $this->logo_file ) ;
			if ( $this->header_logo_width ) {
				$img->width( $this->header_logo_width ) ;
				$w = $this->header_logo_width ;
			} else {
				$img->height( $this->header_logo_height ) ;
				$w = 20 ;
			}
			$img = new td( $img->build() ) ; $img->align( 'l' ) ; $img->valign( 'top' ) ; $img->width( $w ) ;
			$abncss = new cssStyle; $abncss->font( 'Arial' , '7pt' ) ;
			$abn = new td( "ABN: {$this->ABN}" ) ; $abn->addStyle( $abncss ) ; $abn->width( $w ) ; $abn->align('c') ;
		}
		$state = new div( strtoupper( $type ) ) ; $state->addStyle( $statecss ) ;
		if ( $this->form_header_file  ){
			$banner = new image; $banner->source( 'img/' . $this->form_header_file ) ; $banner->addStyle( $bcss ) ;
			$banner = $banner->build() ;
		} else {
			$banner = '' ;
		}
		$banner = new td( $banner . $state->build() ) ; $banner->align( 'r' ) ; $banner->rowspan(2) ; $banner->add( 'nowrap' , 'nowrap' ) ; $banner->width(200) ;
		$blank = new td( '&nbsp;' ) ; $blank->rowspan(2) ;
		$tr = new tr( $img->build() . $blank->build() . $banner->build() ) ;
		$tr2 = new tr( $abn->build() ) ;
		$table = new table( $tr->build() . $tr2->build() ) ; $table->addStyle( $css ) ; $table->width( '100' , '%' ) ;
		//	die ( $table->build() ) ;
		return $table->build() ;
	}

}
