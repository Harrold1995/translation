<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace App\Services\Utility;

use App\Services\Sql\request as standardSqlRequest ;

class queueEmail  extends standardSqlRequest
{

    private static $emailServer;
    private static $serverPassword;

    function __construct()
    {
        self::$emailServer = '192.168.100.202';
        self::$serverPassword = 'greeL35iffy6';
        parent::__construct( 'emails' );
    }

    public static function createEmail($emailTo, $subject, $message, $fileName = '', $originalFileName = '')
    {
        $email =  new self();
        $email->setField('email_to', $emailTo);
        $email->setField('subject', $subject);
        $email->setField('message', $message);
        $email->setField('email_from_id', LOCAL_API_EMAIL_ID);

        //if (isset($params['sent']))
            //$email->setField('sent', $params['sent']);

        if ($fileName != '')
            $email->setField('file_name', $fileName);
        if ($originalFileName != '')
            $email->setField('original_file_name', $originalFileName);


        $email->insert();
        //echo $email->sql;
        return $email->last_insert_id;

    }

    public static function addAttachment($filePathFrom, $filePathTo, $fileName)
    {
        //added this options
        //-o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no
        //as the scp command does not work without it
        //system("sshpass -p '" . self::$serverPassword ."' scp -v -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no ".$filePathFrom.$fileName." admin@".self::$emailServer.":".$filePathTo.$fileName." 2>&1", $output);
        //system("sshpass -p '" . self::$serverPassword ."' scp -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no ".$filePathFrom.$fileName." admin@".self::$emailServer.":".$filePathTo.$fileName." 2>&1", $output);
        exec("sshpass -p '" . self::$serverPassword ."' scp -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no ".$filePathFrom.$fileName." admin@".self::$emailServer.":".$filePathTo.$fileName." 2>&1", $output);

        //echo "sshpass -p '" . self::$serverPassword ."' scp ".$filePathFrom.$fileName." admin@".self::$emailServer.":".$filePathTo.$fileName;
        //print_r($output);
        //print_r($status);
        return 0;
    }

    public static function sendEmail($emailID)
    {
		$mysqli = $mysqli2;
        $email =  new self();
        $email->setField("sent", 1);
        $email->where("email_id", $emailID);
        $email->update();
    }





}
