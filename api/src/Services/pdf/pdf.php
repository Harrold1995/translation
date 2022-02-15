<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 11/7/17
 * Time: 1:41 PM
 */

namespace API\pdf;

use API\email\email_task;
use API\html\cssStyle;
use API\html\div;
use API\model\messageModel;
use API\sql\request as standardSQLrequest;
use API\template\template;
use Mpdf\Mpdf;
use Slim\Http\Response;
use Slim\Http\Request;
use API\stationery\letterheads as letterheads;
use API\stationery\company as company;
use API\utility\queueEmail as queueEmail;


class pdf extends standardSQLrequest
{
    //use returnError ;
    use letterheads;
    private $years = 3;

    function __construct( )
    {
        //parent::__construct( 'contacts' );

    }

    public function confirmRevalidationLetter( Request $request , Response $response , $args )
    {
        $data = $request->getAttribute( 'data' ) ;
        $intID =  $data->fmid;

        $interpreterDetails = $this->getInterpreterDetails($intID);
        $firstJob = $this->getFirstJob($intID);
        $interpretations = $this->getInterpretations($intID);
        $translations = $this->getTranslations($intID);

        $interpreterDetails->recruitment_date = $firstJob->service_date;
        $interpreterDetails->interpretations = $interpretations;
        $interpreterDetails->translations = $translations;

        $body = $this->confirmBodyText($interpreterDetails);
        $obj = (object) [ 'error' => false, 'content' => $body ] ;
        $response = $response->withJson( $obj ) ;
        return $response ;

    }

	//START: sendRevalidationLetterOLD FUNCTION NOT IN USE
	/**
	 * @param Request  $request
	 * @param Response $response
	 * @param          $args
	 *
	 * @return Response
	 * @throws \Mpdf\MpdfException
	 */
	public function sendRevalidationLetterOLD( Request $request , Response $response , $args )
    {
        $data = $request->getAttribute( 'data' ) ;
        $intID =  $data->fmid;
        $oldIntID =  $data->intid;

        $interpreterDetails = $this->getInterpreterDetails($intID);
        $firstJob = $this->getFirstJob($intID);
        $interpretations = $this->getInterpretations($intID);
        $translations = $this->getTranslations($intID);

        $interpreterDetails->recruitment_date = $firstJob->service_date;
        $interpreterDetails->interpretations = $interpretations;
        $interpreterDetails->translations = $translations;

        $header = $this->header();
        $footer = $this->all_graduates_footer();
        $body = $this->body($interpreterDetails);

        $fromDate = $firstJob->service_date_formatted;
        $toDate = date("Y-m-d");
        $fileName = $interpreterDetails->first_name . " " . $interpreterDetails->surname . " NAATI transition letter.pdf";
        $documentBoxID = $this->createPDfRecord($fileName, $oldIntID, $fromDate, $toDate);


        $pdfBody = $body;
        $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir() . '/']);
        $mpdf->SetProtection(array('print'));
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->SetHTMLHeader($header);
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($pdfBody);

		//cannot test this on your local windows pc or mac it returns error since it is looking for the folder to write to,
		//so comment below to test in the local machine
        $mpdf->Output("/var/box/documents/toBox/$documentBoxID.pdf",'F');


        //send an email to contact
        $message = "Hi " .  ucwords(strtolower($interpreterDetails->first_name)) . ",
                                <br><br>
                                Attached to this email is the PDF file for your requested NAATI recertification letter. 
                                <br><br>
                                If you didn't request this file, and you think someone may have accessed your account, please call All Graduates. 
                                <br><br> 
                                Phone: (03) 9605 3000<br>
                                Email: admin@allgraduates.com.au<br>
                                <br><br>
                                Yours Sincerely,
                                <br>
                                All Graduates Team";


//        $to = $interpreterDetails->email;
        $to = 'bhim@allgraduates.com.au'; // uncomment only for testing purpose. comment above line
        $subject = 'Transition Letter';

        $emailID = queueEmail::createEmail($to, $subject, $message, $fileName, "$documentBoxID.pdf");
        $status = queueEmail::addAttachment("/var/box/documents/toBox/", "/Library/WebServer/Documents/attachments/", "$documentBoxID.pdf");
        $status = 0; // uncomment to test in the local machine and comment above line
        if ($status == 0)
            queueEmail::sendEmail($emailID);

        $obj = (object) [ 'error' => false ] ;
        $response = $response->withJson( $obj ) ;
        return $response ;

    }
	//END: ABOVE sendRevalidationLetterOLD NOT IN USE

	/**
	 * @param Request  $request
	 * @param Response $response
	 * @param          $args
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function sendRevalidationLetter( Request $request , Response $response , $args )
	{
		$message = new messageModel();
		$currentDate = date("jS"). " of ". date("F Y");

		$data = $request->getAttribute( 'data' ) ;
		$intID =  $data->fmid;
		$oldIntID =  $data->intid;
		$interpreterDetails = $this->getInterpreterDetails($intID);
		$firstJob = $this->getFirstJob($intID);
		$interpretations = $this->getInterpretations($intID);
		$translations = $this->getTranslations($intID);

		$interpreterDetails->firstName = ucwords(strtolower($interpreterDetails->first_name));
		$interpreterDetails->recruitment_date = $firstJob->service_date;
		$interpreterDetails->interpreterName = ucwords(strtolower($interpreterDetails->first_name. " " . $interpreterDetails->surname));
		$interpreterDetails->interpreterAddress = ucwords(strtolower($interpreterDetails->address));
		$interpreterDetails->interpreterAddress2 = ucwords(strtolower($interpreterDetails->suburb. " " . $interpreterDetails->state . " " . $interpreterDetails->postcode));
		$interpreterDetails->interpreterDescription = ($interpreterDetails->casual == 1 || $interpreterDetails->casual == 3 ) ? 'casual' : 'contractor';
		$interpreterDetails->cpnOrNumLabel = ($interpreterDetails->naati_cpn) ? '' : 'number ';
		$interpreterDetails->cpnOrNum = ($interpreterDetails->naati_cpn) ? $interpreterDetails->naati_cpn : $interpreterDetails->naati_number;
		$languages = $this->languagesBody($interpretations, $translations);

		$fromDate = $firstJob->service_date_formatted;
		$toDate = date("Y-m-d");
		$fileName = $interpreterDetails->first_name . " " . $interpreterDetails->surname . " NAATI Re-certification letter.pdf";
		$templateVariableTLEA = (object) array ("current_date" => $currentDate, "interpreterDetails" => $interpreterDetails, "languages" => $languages);
		$transitionLetterEmailTemplateAttach = new standardSQLrequest('sms_defaults');
		$transitionLetterEmailTemplateAttach->addField('sms_default_id');
		$transitionLetterEmailTemplateAttach->where('title', 'Transition Letter');
		$transitionLetterEmailTemplateAttach->where('template_type', 'Interpreter Letters');
		$transitionLetterEmailTemplateAttach->select();
		$transitionLetterEmailTemplateAttach = template::get( $transitionLetterEmailTemplateAttach->rows[ 0 ], $templateVariableTLEA );

		$documentBoxID = $this->createPDfRecord($fileName, $oldIntID, $fromDate, $toDate);
		$mpdf = new Mpdf(['tempDir' => sys_get_temp_dir() . '/']);

		$pageCount = $mpdf->setSourceFile('/var/www/global/letterhead/AG_Letterhead_FA.pdf');
		$tplId = $mpdf->importPage($pageCount, 'ArtBox');
		$mpdf->UseTemplate($tplId);
		$mpdf->SetProtection(array('print'));
		$mpdf->setAutoTopMargin = 'pad';
		$mpdf->setAutoBottomMargin = 'pad';
		$mpdf->WriteHTML($transitionLetterEmailTemplateAttach);
		//Cannot test this on your local windows pc or mac it returns error since it is looking for the folder to write to,
		//So comment below to test in the local machine
		$mpdf->Output("/var/box/documents/toBox/$documentBoxID.pdf",'F');


		$templateVariableTLE = (object) array ("first_name" => ucwords(strtolower($interpreterDetails->first_name)));
		$transitionLetterEmailTemplate = new standardSQLrequest('sms_defaults');
		$transitionLetterEmailTemplate->addField('sms_default_id');
		$transitionLetterEmailTemplate->where('title', 'Transition Letter Email');
		$transitionLetterEmailTemplate->where('template_type', 'Interpreter Letters');
		$transitionLetterEmailTemplate->select();

        $to = $interpreterDetails->email; // Comment this if have to test
//		$to = 'bhim@allgraduates.com.au'; // Uncomment only for testing purpose. comment above line
		$subject = 'Recertification Letter';
		$templateCreateEmail = template::get( $transitionLetterEmailTemplate->rows[ 0 ], $templateVariableTLE );

		$emailID = queueEmail::createEmail($to, $subject, $templateCreateEmail, $fileName, "$documentBoxID.pdf");
		$status = queueEmail::addAttachment("/var/box/documents/toBox/", "/Library/WebServer/Documents/attachments/", "$documentBoxID.pdf");
//		$status = 0; // Uncomment to test in the local machine and comment above line
		if ( $status == 0 ) {
			queueEmail::sendEmail($emailID);
			$result = $message->returnMessage(false, 'Email Sent', $languages );
		}
		else {
			$result = $message->returnMessage(true, 'Email Failed!');
		}

		return $response->withJson($result);
	}

    private function getInterpreterDetails($intID) {
        $interpreter = new standardSQLrequest( 'interpreters' ) ;
        $interpreter->addField( 'address' );
        $interpreter->addField( 'suburb' );
        $interpreter->addField( 'postcode' );
        $interpreter->addField( 'first_name' );
        $interpreter->addField( 'surname' );
        $interpreter->addField( 'casual' );
        $interpreter->addField( 'email' );
        $interpreter->addField( 'naati_number' );
        $interpreter->addField( 'naati_cpn' );
        $interpreter->addFieldAlias( 'casual_description', 'Description', 'casual_cats' );
        $interpreter->addLeftJoin( 'casual_cats' , 'casual', 'num');
        $interpreter->addFieldCalc( 'state' , "state_from_postcode( postcode)" );
        $interpreter->where( 'fm_interpreter_id' , $intID ) ;
        $interpreter->select();

        $interpreter->rows[0]->address = iconv('CP1252', 'ASCII//TRANSLIT', $interpreter->rows[0]->address);
        $interpreter->rows[0]->suburb = iconv('CP1252', 'ASCII//TRANSLIT', $interpreter->rows[0]->suburb);
        $interpreter->rows[0]->first_name = iconv('CP1252', 'ASCII//TRANSLIT', $interpreter->rows[0]->first_name);
        $interpreter->rows[0]->surname = iconv('CP1252', 'ASCII//TRANSLIT', $interpreter->rows[0]->surname);

        return $interpreter->rows[0];
    }

    private function getFirstJob($intID)
    {
        $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") - $this->years)); //minus 3 years

        $jobs = new standardSQLrequest('jobs');
        $jobs->addFieldCalc('service_date', "DATE_FORMAT(`service date` , '%D of %M %Y' )");
        $jobs->addFieldCalc('service_date_formatted', "DATE_FORMAT(`service date` , '%Y-%m-%d' )");
        $jobs->where('int id', $intID);
        $jobs->where("`service date` >= '$date' [calc]", '[isBoolean]');
        $jobs->in( 'inter trans', array('inter', 'Ph Int', 'Video'), 'jobs');
        $jobs->addOrderBy('service date');
        $jobs->recordLimit = 1;
        $jobs->select();


        $translations = new standardSQLrequest('jobs');
        $translations->addJoin('payment_lines', 'ref no', 'ref_no');
        $translations->addField('date due to us', 'jobs', 'date_due_to_us');
        $translations->addField('date due to client', 'jobs', 'date_due_to_client');
        $translations->addField('date dispatched', 'jobs', 'date_dispatched');
        $translations->addField('booking_received', 'payment_lines');
        $translations->where("payment_lines.`booking_received` >= '$date' [calc]", '[isBoolean]');
        $translations->where('int id', $intID);
        $translations->where('inter trans', 'Trans', '=', 'jobs');
        $translations->recordLimit = 1;
        $translations->select();

        //echo $translations->sql;
        //exit;

        $translationsDate = 0;
        $interpretingDate = 0;
        $startDate = 0;
        if ($translations->found) {
            //if the value of the field is blank forward to 3 years so the script wont select it as the earlist job
            //*3 years is the current settings for this NAATI revalidation letter
            $dateDueToUs = ($translations->rows[0]->date_due_to_us == '' || $translations->rows[0]->date_due_to_us == '0000-00-00') ?  mktime(0, 0, 0, date("m"), date("d"), date("Y") + $this->years) : strtotime($translations->rows[0]->date_due_to_us);
            $dateDueToClient = ($translations->rows[0]->date_due_to_client == '' || $translations->rows[0]->date_due_to_client == '0000-00-00') ? mktime(0, 0, 0, date("m"), date("d"), date("Y") + $this->years) : strtotime($translations->rows[0]->date_due_to_client);
            $dateDispatched = ($translations->rows[0]->date_dispatched == '' || $translations->rows[0]->date_dispatched == '0000-00-00') ? mktime(0, 0, 0, date("m"), date("d"), date("Y") + $this->years) : strtotime($translations->rows[0]->date_dispatched);
            $bookingReceived = ($translations->rows[0]->booking_received == '' || $translations->rows[0]->booking_received == '0000-00-00') ? mktime(0, 0, 0, date("m"), date("d"), date("Y") + $this->years) : strtotime($translations->rows[0]->booking_received);

            $translationsDate = min([$dateDueToUs, $dateDueToClient, $dateDispatched, $bookingReceived]);
        }

        if ($jobs->found)
            $interpretingDate = strtotime($jobs->rows[0]->service_date_formatted);


        if ($translationsDate > 0 || $interpretingDate > 0) {
            if ($translationsDate == 0)
                $startDate = $interpretingDate;
            else if ($interpretingDate == 0)
                $startDate = $translationsDate;
            else
                $startDate = min([$translationsDate, $interpretingDate]);
        }

        if ($startDate == 0) {
            $lastThreeYears = mktime(0, 0, 0, date("m"), date("d"), date("Y") - $this->years); //minus 3 years
            $serviceDate = date("jS", $lastThreeYears) . " of " . date("F Y", $lastThreeYears);
            $serviceDateFormatted = date("Y-m-d", $lastThreeYears);

            $jobs->rows[0]->service_date = $serviceDate;
            $jobs->rows[0]->service_date_formatted = $serviceDateFormatted;
            $serviceDate = $jobs->rows[0];
        } else {
            $serviceDate = date("jS", $startDate) . " of " . date("F Y", $startDate);
            $serviceDateFormatted = date("Y-m-d", $startDate);

            $jobs->rows[0]->service_date = $serviceDate;
            $jobs->rows[0]->service_date_formatted = $serviceDateFormatted;
            $serviceDate = $jobs->rows[0];
        }
        return $serviceDate;
    }

    private function getInterpretations($intID) {
        //get date less 3 years
        $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") - $this->years) );

        $interpreter = new standardSQLrequest( 'interpreters' ) ;
        $interpreter->addField( 'language', 'jobs');
        $interpreter->addField( 'language id', 'jobs', 'language_id');
        $interpreter->addField( 'job_session_id', 'jobs');
        $interpreter->in( 'inter trans', array('inter', 'Ph Int', 'Video'), 'jobs');
        $interpreter->addJoin( 'jobs' , 'fm_interpreter_id', 'int id');
        $interpreter->where( 'int id' , $intID, '=', 'jobs' ) ;
        $interpreter->where( "`service date` >= '$date' [calc]", '[isBoolean]', '', 'jobs' ) ;
        $interpreter->select();

        $data = [];
        foreach ($interpreter->rows as $row) {
            $jobSessionID = $row->job_session_id;
            $language = $row->language;
            $language = trim(ucwords(strtolower($language)));
            $languageID = $row->language_id;
            $currentNaatiLevel = 0;

            //if it is a dhs session or with any with job session
            if ($jobSessionID != '') {
                //get naati level of current language
                $naatiLevel = new standardSQLrequest( 'interpreter_language_link' ) ;
                $naatiLevel->where( 'Interpreter ID' , $intID) ;
                $naatiLevel->where( 'language id' , $languageID) ;
                $naatiLevel->addField( 'l1 int NAATI' , "interpreter_language_link", 'naatiLevel' );
                $naatiLevel->select();

                if ($naatiLevel->found)
                    $currentNaatiLevel = ($naatiLevel->rows[0]->naatiLevel == '') ? 0 : $naatiLevel->rows[0]->naatiLevel;

                //search for other languages in a session that is has a higher NAATI accreditation
                //tables
                //client_session_languages

                //get languages from
                //job_sessions
                //interpreter_language_link
                $languageIDS = '';
                $sessionLanguages = new standardSQLrequest( 'job_sessions' ) ;
                $sessionLanguages->addJoin( 'client_session_languages' , 'client_session_id', 'client_session_id');
                $sessionLanguages->addFieldCalc( 'languages' , "group_concat( client_session_languages.language_id )" );
                $sessionLanguages->select();
                if ($sessionLanguages->found)
                    $languageIDS = $sessionLanguages->rows[0]->languages;

                //compare current to other languages in session if other languages has higher naati accreditation
                if ($languageIDS != '') {
                    $languageIDS = explode(",", $languageIDS);
                    $otherNaati = new standardSQLrequest( 'interpreter_language_link' ) ;
                    $otherNaati->addJoin( 'languages' , 'language id', 'language id');
                    $otherNaati->where( 'interpreter id' , $intID) ;
                    $otherNaati->where( 'language id' , $languageID, '!=') ;
                    $otherNaati->in( 'language id' , $languageIDS) ;
                    $otherNaati->where( 'l1 int NAATI' , $currentNaatiLevel, '>') ;
                    $otherNaati->addField( 'language' , "languages" );
                    $otherNaati->select();

                    //modifiy the language if there is a higher naati level
                    if ($otherNaati->found)
                        $language = $otherNaati->rows[0]->language;

                }
            }

            if (isset($data[$language])) {
                $data[$language]->job_count++;
            } else {
                $data[$language] = (object) ["job_count" => 1, "language" => $language];
            }


        }
        return $data;


        //return $interpreter->rows;
    }

    private function getTranslations($intID) {
        //get date less 3 years
        $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") - $this->years) );

        $interpreter = new standardSQLrequest( 'interpreters' ) ;
        $interpreter->addJoin( 'jobs' , 'fm_interpreter_id', 'int id');
        $interpreter->addJoin('payment_lines', 'ref no', 'ref_no', 'jobs');
        $interpreter->addCount( 'job_count') ;
//        $interpreter->addFieldCalc( 'job_count' , "COUNT( jobs.`trans lang out of` )" );
        $interpreter->addField( 'language', 'jobs');
        $interpreter->addField( 'trans lang into', 'jobs', 'trans_lang_into');
        $interpreter->addField( 'trans lang out of', 'jobs', 'trans_lang_out_of');
        $interpreter->where( 'int id' , $intID, '=', 'jobs' ) ;
        $interpreter->where( 'inter trans', 'Trans', '=', 'jobs' ) ;
        $interpreter->where("payment_lines.`booking_received` >= '$date' [calc]", '[isBoolean]');
        $interpreter->addGroupBy('trans lang into', 'jobs');
        $interpreter->addGroupBy('trans lang out of', 'jobs');
        $interpreter->select();

        //echo $interpreter->sql;
        return $interpreter->rows;
    }


    private function createPDfRecord($fileName, $intID, $fromDate, $toDate) {

        //write the record to document_box
        $box = new standardSQLrequest( 'documents_box' ) ;
        $box->setField( 'mime_type' , 'application/pdf') ;
		$box->setField( 'file_name' , $fileName) ;
		$box->setField( 'ext' , 'pdf') ;
        $box->insert() ;

        $documentBoxID = $box->last_insert_id;

        //update filename
        $updateBox = new standardSQLrequest( 'documents_box' ) ;
        $updateBox->setField( 'file_name' , "$documentBoxID.pdf") ;
        $updateBox->where("document_box_id", $documentBoxID);
        $updateBox->update();

        return $documentBoxID;




    }


}

