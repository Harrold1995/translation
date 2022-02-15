<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 25/1/17
 * Time: 2:15 PM
 */

namespace API\utility;


class localAPI
{

	function __construct()
	{

	}

    public static function sendEmail($params)
    {
        $context  = self::buildParams($params);
        $result = file_get_contents(EMAIL_API . "local_api/public/send_email", false, $context);
        return $result;

    }


    public static function sendEmailAttachment($params)
    {
        $context  = self::buildParams($params);
        $result = file_get_contents(EMAIL_API . "local_api/public/send_email_attachment", false, $context);
        return $result;
    }

    private static function buildParams($params) {
	    $params['emailID'] = LOCAL_API_EMAIL_ID;
        $postdata = http_build_query(
            $params
        );

        $options = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($options);
        return $context;
    }

}

