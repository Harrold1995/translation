<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/2/17
 * Time: 11:29 AM
 */

namespace API\stationery;


use API\html\cssStyle;
use API\html\div;
use API\html\span;
use API\html\table;
use API\html\td;
use API\html\tr;
use API\functions\utility ;
use Mpdf\Mpdf;

trait letterheads
{

	function interpreterline_footer() {
		$table = new table();
		$s = new td( '&nbsp;' ) ; $s->width( '10mm' ) ;
		$head = new cssStyle(); $head->font( 'Bodoni' , '8pt' , '#3576B8' ) ;
		$stand = new cssStyle; $stand->font( 'Bodoni Book' , '8pt' , '#3576B8' ) ;
		$headgr = new cssStyle; $headgr->font( 'Bodoni Book' , '8pt' , '#3C9A4C' ) ;
		$melb = new div( 'Melbourne' ) ; $melb->addStyle( $head ) ;
		$melba = new div( 'Level 8, 343 Lt Collins Street<br>Melbourne, Victoria 3000' ) ; $melba->addStyle( $stand ) ;
		$syd = new div( 'Sydney' ) ; $syd->addStyle( $head ) ;
		$syda = new div( 'Level 2, 50 York Street<br>Sydney, NSW 2000' ) ; $syda->addStyle( $stand ) ;
		$telh = new span( 'Telephone: ' ) ; $telh	->addStyle( $headgr ) ;
		$faxh = new span( 'fax: ' ) ; $faxh->addStyle( $headgr ) ;
		$bookh = new span( 'Bookings Interpreterline: ' ) ; $bookh->addStyle( $headgr ) ;
		$emailh = new span( 'Email: ' ) ; $emailh->addStyle( $headgr ) ;
		$tel = new span( '1300 134 746' ) ; $tel->addStyle( $stand ) ;
		$fax = new span( '1300 134 251' ) ; $fax->addStyle( $stand ) ;
		$book = new span( '1300 739 731' ) ; $book->addStyle( $stand ) ;
		$email = new span( 'info@interpreterline.com.au' ) ; $email->addStyle( $stand ) ;
		$www = new div( 'www.interpreterline.com.au' ) ; $www->addStyle( $head ) ;
		$left = new td( $melb->build() . $melba->build() ) ;
		$left1 = new td( $syd->build() . $syda->build() ) ;
		$right = new td( $bookh->build() . $book->build() . '<br>' . $emailh->build() . $email->build() . $www->build() ) ;
		$right1 = new td( $telh->build() . $tel->build() . '<br>' . $faxh->build() . $fax->build() ) ; $right1->valign( 'top' ) ;
		$tr = new tr( $left->build() . $s->build() . $left1->build() . $s->build() . $right1->build() . $s->build() . $right->build() ) ;
		$table->data = $tr->build() ;
		$css = new cssStyle; $css->width( '100' , '%' ) ; $css->add( 'text-align' , 'center' ) ;  $css->add( 'padding-left' , '5mm' ) ;
		$div = new div( $table->build() ) ; $div->addStyle( $css ) ;
		return $div->build() ;
	}

	function meaningful_exchange_footer() {
//		$table = new table; $table->width( 100 , '%' ) ; $table->cellspacing('0') ; $table->cellpadding('0') ;
//		$foot = new image( 'img/me_footer_text.png' ) ;
//		$foot = new td( $foot->build() ) ;
//		$tr = new tr( $foot->build() ) ;
//		$table->data = $tr->build() ;
		$greyt = new cssStyle; $greyt->font( 'Gill Sans Light' , '8pt' , '#4c4d4f' ) ;
		$greent = new cssStyle; $greent->font( 'Gill Sans' , '8pt' , '#68b458' , 'bold' ) ;
		$addr = new td( 'Unit 9, 51-55 City Road<br>Southbank Victoria 3006 Australia' ) ; $addr->addStyle( $greyt ) ; $addr->width( 35 , '%' ) ;
		$email = new td( 'info@meaningfulexchange.com.au<br>www.meaningfulexchange.com.au' ) ; $email->addStyle( $greyt ) ;
		$ebold = new td( 'E:<br>W:' ) ; $ebold->addStyle( $greent ) ;
		$tr1 = new tr( $ebold->build() . $email->build() ) ;
		$right = new table( $tr1->build() ) ; $right->cellspacing('0') ; $right->add( 'border' , '0' ) ; $right->cellpadding( '0' ) ;
		$right = new td( $right->build() ) ; $right->align('r') ; $right->width( 35 , '%' ) ;
		$pf = new td( 'P:<br>F:' ) ; $pf->addStyle( $greent ) ;
		$cont = new td( '1300 854 799<br>1300 134 251' ) ; $cont->addStyle( $greyt ) ;
		$tr = new tr( $pf->build() . $cont->build() ) ;
		$cont = new table( $tr->build() ) ;
		$cont = new td( $cont->build() ) ; $cont->align('c') ; $cont->width( 30 , '%' ) ;
		$tr = new tr( $addr->build() . $cont->build() . $right->build() ) ;
		$foot = new table( $tr->build() ) ; $foot->width( 100 , '%' ) ;
		return $foot->build() ;
	}

	function all_graduates_footer() {
		$headcs = new cssStyle;
		$headcs->font( 'Arial Narrow' , '10pt' , '#134C99' ) ;

        $css = new cssStyle;
        $css->pad('top', 2, 'em');

		$addcs = new cssStyle;
		$addcs->font( 'Arial Narrow' , '8pt' ) ;
		$addcs->border( 'l' , 'grey' , '1px' ) ;
		$addcs->pad( 'lr' , 4 , 'mm' ) ;
		$addcs->add( 'line-height' , '15pt' ) ;

		$bullcs = new cssStyle;
		$bullcs->font( 'Arial Narrow' , '10pt' , '#FF9E33' , 'bold' ) ;

		$bullet = new span( ' &bull; ' ) ;
		$bullet->addStyle( $bullcs ) ;
		$b = $bullet->build() ;

		$msg = new div( 'Making<br>Communication' ) ;
		$msg->addStyle( $headcs ) ;
		$msg = new td( $msg->build() . 'Easy & Accessible' );
		$msg->addStyle( $addcs ) ;

		$ag = new div( 'All Graduates Interpreting and Translating' ) ;
		$ag->addStyle( $headcs ) ;
		$ag = new td( $ag->build() . 'ABN 93 452 691 725<br>admin@allgraduates.com.au<br>allgraduates.com.au' );
		$ag->addStyle( $addcs ) ;

		$melb = new div( 'Head Office:' ) ;
		$melb->addStyle( $headcs ) ;
		$melb = new td( $melb->build() . 'Unit 9, 51-55 City Road<br>Southbank VIC 3006<br>P 03 9605 3000 F 03 9600 0048' ) ;
		$melb->addStyle( $addcs ) ;


		$serv = new div( 'Servicing:' ) ;
		$serv->addStyle( $headcs ) ;
		$serv = new td( $serv->build() . 'Victoria, New South Wales, Western Australia,<br>Queensland, Tasmania, South Australia,<br>Northern Territory, Australian Capital Territory' ) ;
		$serv->addStyle( $addcs ) ;


		$tr = new tr( $msg->build() . $ag->build() . $melb->build() . $serv->build() ) ;

		$table = new table( $tr->build() ) ;
		$table->width( 100 , '%' ) ;
		$table->cellpadding( '0' ) ;
		$table->cellspacing( '0' ) ;
        $table->addStyle( $css ) ;

		//	$table->display() ;
//		$css = new cssStyle;
//		$css->font( 'Arial Narrow' , '18pt' , '#134C99'  ) ;
//		$css->width( 100 , '%' ) ;// $css->align('c') ;
//
//        $div = new div( 'Industry leaders in providing trained and qualified interpreters and translators' ) ;
//        $div->addStyle( $css ) ;
//        $div->align( 'justify' ) ;
//		//	$div->display() ;
//		$css = new cssStyle;
//		$css->font( 'Arial Narrow' , '10pt' , '#134C99' , 'upper' ) ;
//		$css->width( 100 , '%' ) ;
//		$css->align( 'left' ) ;
//		$css->add('letter-spacing','.02em' ) ;
//		$byline = new div( "certified translations $b on site, telephone and video interpreting $b multilingual desktop publishing" ) ;
//		$byline->addStyle( $css ) ;
		//	$byline->display() ; exit ;
		return $table->build() ;
	}

	function nex_footer( $totals = '' ) {
		$headcs = new cssStyle; $headcs->font( 'Arial Narrow' , '10pt' , '#134C99' , 'bold' ) ;
		$addcs = new cssStyle; $addcs->font( 'Arial Narrow' , '8pt' , '#134C99' ) ; $addcs->border( 'l' , '#FF9E33' , '2px' ) ; $addcs->pad( 'lr' , 4 , 'mm' ) ;
		$addcs->add( 'line-height' , '10pt' ) ;
		$addcslast = new cssStyle; $addcslast->font( 'Arial Narrow' , '9pt' , '#134C99' ) ; $addcslast->border( 'lr' , '#FF9E33' , '2px' ) ; $addcslast->pad( 'lr' , 4 , 'mm' ) ;
		$addcslast->add( 'line-height' , '10pt' ) ;
		$bullcs = new cssStyle; $bullcs->font( 'Arial Narrow' , '10pt' , '#FF9E33' , 'bold' ) ;
		$bullet = new span( ' &bull; ' ) ; $bullet->addStyle( $bullcs ) ; $b = $bullet->build() ;
		$melb = new div( 'Melbourne' ) ; $melb->addStyle( $headcs ) ;
		$melb = new td( $melb->build() . 'Unit 9, 51-55 City Road<br>Southbank Victoria 3006<br>Ph (03) 9605 3000 Fax (03) 9600 0048' ) ;
		$melb->addStyle( $addcs ) ;
		$syd = new div( 'Sydney' ) ; $syd->addStyle( $headcs ) ;
		$syd = new td( $syd->build() . 'Level 1, The \'Karstens\' floor<br>111 Harrison Street, Sydney NSW 2000<br>Ph 1300 134 746 Fax 1300 134 251' ) ; $syd->addStyle( $addcs ) ;
		$ag = new div( 'All Graduates Interpreting and Translating Services' ) ; $ag->addStyle( $headcs ) ;
		$ag = new td( $ag->build() . '<br>Email admin@allgraduates.com.au<br>www.allgraduates.com.au' ); $ag->addStyle( $addcslast ) ;
		$tr = new tr( $melb->build() . $syd->build() . $ag->build() ) ;
		$table = new table( $tr->build() ) ; $table->width( 100 , '%' ) ; $table->cellpadding( '0' ) ; $table->cellspacing( '0' ) ;
		//	$table->display() ;
		$css = new cssStyle; $css->font( 'Arial Narrow' , '18pt' , '#134C99' , 'upper' ) ; $css->add( 'letter-spacing' , '.529em' ) ; $css->width( 100 , '%' ) ;// $css->align('c') ;
		$div = new div( 'Foreign Language Specialists' ) ; $div->addStyle( $css ) ; $div->align( 'justify' ) ;
		//	$div->display() ;
		$css = new cssStyle; $css->font( 'Arial Narrow' , '10pt' , '#134C99' , 'upper' ) ; $css->width( 100 , '%' ) ; $css->align( 'center' ) ; $css->add('letter-spacing','.02em' ) ;
		$byline = new div( "certified translations $b on site interpreting $b telephone interpreting $b multilingual desktop publishing" ) ; $byline->addStyle( $css ) ;
		//	$byline->display() ; exit ;
//		die( $totals . $table->build() . $div->build() . $byline->build() ) ;
		return $totals . $table->build() . $div->build() . $byline->build() ;
	}

	function header()
    {
        $css = new cssStyle; $css->font( 'Arial Narrow' , '18pt' , '#134C99'  ) ; $css->add( 'letter-spacing' , '0.2em' ) ; $css->width( 100 , '%' ) ;// $css->align('c') ;
        $css->pad('bottom', 1, 'em');
        $div = new div( '<img src="https://api.allgraduates.com.au/img/logo3.png" />' ) ; $div->addStyle( $css ) ; $div->align( 'left' ) ;
        //	$div->display() ;
        //	$byline->display() ; exit ;
        return $div->build();

    }

	public function languagesBody($interpretations, $translations) {
		$languages = [];
		foreach ($interpretations as $language) {
			array_push($languages, 'In ' . ucwords($language->language) . ' - ' . $language->job_count . ' assignments of interpreting;');
		}

		foreach ($translations as $language) {
			array_push($languages, 'In ' . ucwords($language->trans_lang_out_of) . ' to ' . ucwords($language->trans_lang_into) . ' - ' . $language->job_count . ' assignments of translation;');
		}

		return $languages;
	}

    public function body($interpreterDetails) {
	    $currentDate = date("jS"). " of ". date("F Y");
	    $interpreterName = ucwords(strtolower($interpreterDetails->first_name. " " . $interpreterDetails->surname));
        $interpreterAddress = ucwords(strtolower($interpreterDetails->address));
        $interpreterAddress2 = ucwords(strtolower($interpreterDetails->suburb. " " . $interpreterDetails->state . " " . $interpreterDetails->postcode));
        $interpreterDescription = ($interpreterDetails->casual == 1 || $interpreterDetails->casual == 3 ) ? 'casual' : 'contractual';

        $naati = $interpreterDetails->naati_number;
        $recruitmentDate = $interpreterDetails->recruitment_date;

        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'left' ) ;  //$css->pad('top', 8, 'em');
        $interpreterHeader = new div( $currentDate ."<br><br>$interpreterName<br>". $interpreterAddress ."<br>". $interpreterAddress2 ."<br><br><br>" );
        $interpreterHeader->addStyle( $css ) ;

        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'left' ) ; $css->pad('bottom', -2, 'em');
        $para1 = new div( "<p>The purpose of this letter is to verify the volume of interpreting/translating assignment undertaken by $interpreterName in support of 
                                    an application to NAATI for recertification.</p>" ) ;
        $para1->addStyle( $css ) ;



        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'left' ) ;
        $para2 = new div( "<p>". $interpreterName. " has been engaged by All Graduates on a $interpreterDescription basis as an Interpreter and/or Translator. 
                                $interpreterName, NAATI number $naati has completed the following interpreting/translating assignments during the period $recruitmentDate to $currentDate. This letter has been issued upon the request of $interpreterName.</p>" ) ;
        $para2->addStyle( $css ) ;

        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'center' ); $css->pad('bottom', -2, 'em');
        $title = new div( "<u>TO WHOM IT MAY CONCERN</u><br><br>" );
        $title->addStyle( $css ) ;

        $languages = '';
        foreach ($interpreterDetails->interpretations as $language) {
            $languages .= '- In ' . ucwords($language->language) . ' - ' . $language->job_count . ' assignments of interpreting;<br>';
        }

        foreach ($interpreterDetails->translations as $language) {
            $languages .= '- In ' . ucwords($language->trans_lang_out_of) . ' to ' . ucwords($language->trans_lang_into) . ' - ' . $language->job_count . ' assignments of translation;<br>';
        }
        $languages .= '<br>';

        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'left ' ) ; $css->pad('left', 2, 'em'); $css->pad('bottom', -2, 'em');
        $languages = new div( $languages);
        $languages->addStyle( $css ) ;


        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'left' ) ; $css->pad('bottom', -3.5, 'em');
        $para3 = new div( "<p>For any further information or clarification please email: recruitment@allgraduates.com.au<p>");
        $para3->addStyle( $css ) ;

        $css = new cssStyle; $css->font( 'Arial Narrow' , '9pt' , '#000') ; $css->width( 100 , '%' ) ; $css->align( 'left' ) ;
        $para4 = new div( "<p>_________________________________________________<br>
                                    Disclaimer: This letter has been auto created from the All Graduates booking
                                    system and based on the requirements of NAATI for the purposes of a
                                    practitioner's application to NAATI for recertification. Whilst all attempts to ensure the contents are accurate, no
                                    attempt has been made to cross check to validate it. Further, the receiver or any
                                    persons wishing to act on its contents is solely responsible to undertaken its own
                                    review to ensure it is consistent with its own records.</p>") ;
        $para4->addStyle( $css ) ;

        $css = new cssStyle; $css->font( 'Arial Narrow' , '11pt' , '#000' ) ; $css->width( 100 , '%' ) ; $css->align( 'left' ) ; $css->pad('bottom', -2, 'em');
        $allGraduatesSignatory = new div( "Yours sincerely,<br><img src='http://interpretersv2.allgraduates.com.au/api/img/grace.png' /><br><strong>Grace Goh<br>Chief Operation Officer</strong><br>" );
        $allGraduatesSignatory->addStyle( $css ) ;

        return $interpreterHeader->build() . $title->build() . $para1->build() . $para2->build() . $languages->build() . $para3->build() . $para4->build() . $allGraduatesSignatory->build();

    }

    function generic_letterhead( Mpdf $pdf , $html , $template = 'me_letterhead.pdf' , $top = 40 , $width = 135 , $font = 'Arial' , $size = '10pt' ) {

		$pdf->SetImportUse();
		$pagecount = $pdf->SetSourceFile( "img/$template" );
		$tplId = $pdf->ImportPage($pagecount) ;
		$pdf->SetPageTemplate($tplId) ;
		$pdf->SetTopMargin( $top ) ;
		$css = new cssStyle; $css->width( $width ,'mm' ) ; $css->font( $font , $size ) ;
		$div = new div( $html ) ; $div->addStyle( $css ) ;
		$pdf->WriteHTML( utility::convert_smart_quotes( $div->build() ) ) ;
	}

    public function confirmBodyText($interpreterDetails) {
        $currentDate = date("jS"). " of ". date("F Y");
        $interpreterName = ucwords(strtolower($interpreterDetails->first_name. " " . $interpreterDetails->surname));
        $interpreterDescription = ($interpreterDetails->casual == 1 || $interpreterDetails->casual == 3 ) ? 'casual' : 'contractual';

		$cpnOrNumLabel = ($interpreterDetails->naati_cpn) ? '' : 'number ';
		$cpnOrNum = ($interpreterDetails->naati_cpn) ? $interpreterDetails->naati_cpn : $interpreterDetails->naati_number;
        $recruitmentDate = $interpreterDetails->recruitment_date;


        $css = new cssStyle; $css->align( 'left' ) ;
        $content = new div( "<p>". $interpreterName. " has been engaged by All Graduates on a casual/contractor basis as an Interpreter and/or Translator. 
                                $interpreterName, NAATI $cpnOrNumLabel$cpnOrNum has completed the following interpreting/translating assignments during the period $recruitmentDate to $currentDate.</p><br>" ) ;
        $content->addStyle( $css ) ;

        $languages = '';

        foreach ($interpreterDetails->interpretations as $language) {
            $languages .= '- In ' . ucwords($language->language) . ' - ' . $language->job_count . ' assignments of interpreting;<br>';
        }
        foreach ($interpreterDetails->translations as $language) {
            $languages .= '- In ' . ucwords($language->trans_lang_out_of) . ' to ' . ucwords($language->trans_lang_into) . ' - ' . $language->job_count . ' assignments of translation;<br>';
        }
        $languages .= '<br>';

        $css = new cssStyle; $css->width( 100 , '%' ) ; $css->align( 'left ' ) ; $css->pad('left', 2, 'em');
        $languages = new div( $languages);
        $languages->addStyle( $css ) ;


        return $content->build() . $languages->build();
    }


	function all_graduates_letterhead( $pdf , $html ) { $this->generic_letterhead( $pdf , $html , 'ag_letterhead.pdf' , 45 , 190 ) ; }
	function meaningful_exchange_letterhead( $pdf  , $html ) { $this->generic_letterhead( $pdf , $html ) ; }
	function interpreterline_letterhead( $pdf , $html ) { $this->generic_letterhead( $pdf , $html , 'il_letterhead.pdf' , 45 , 190 ) ; }

}

