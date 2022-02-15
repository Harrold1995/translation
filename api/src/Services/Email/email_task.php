<?php

namespace API\email;

use API\curl\post;
use API\sql\request;

class email_task extends request
{
    public function __construct ()
    {

    }

	/**
	 * @param     $email
	 * @param     $subject
	 * @param     $message
	 * @param int $email_id
	 *
	 * @return mixed|string
	 * @throws \Exception
	 */
	public static function sendEmail($email, $subject, $message, $email_id = 4)
    {
        $url = '';
        $serverAddress = $_SERVER[ 'SERVER_ADDR' ];

        switch ($serverAddress) {
            case "10.64.7.194":
                $url = 'http://10.64.7.194/local_api/public/email/send';
                break;
            case "192.168.100.206":
                $url = 'http://192.168.100.206/local_api/public/email/send';
                break;
            case "127.0.0.1" || "localhost":
                $url = 'http://127.0.0.1:8888/local_api/public/email/send';
                break;
            case "::1":
                $url = 'http://127.0.0.1:8888/local_api/public/email/send';
                break;
        }

        $data = array(
            'emailTo' => $email,
            'subject' => $subject,
            'message' => $message,
            'emailID' => $email_id
        );

        $response = post::call($url, $data);

//        print_r( $response ); exit;

        $response = json_decode($response);

        if ($response->error) {
            throw new \Exception($response->errorMessage);
        }
        else {
            return $response;
        }
    }
}
