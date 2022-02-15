<?php
/**
 * Created by PhpStorm.
 * User: satish
 * Date: 09/08/19
 * Time: 3:10 PM
 */

namespace API\staff;

use API\curl\post;
use API\interpreter\details;
use API\interpreter\interpreterObject;
use API\jwt\token;
use API\model\messageModel;
use API\sql\request;
use API\config\app_config;
use Exception;
use Slim\Http\Response as apiResponse;
use Slim\Http\Request as apiRequest;


class account extends request
{
    public function __construct ()
    {
        parent::__construct('staff');
    }

    public function confirm_login (apiRequest $request, apiResponse $response)
    {
        $param = $request->getParsedBody();
        $user = $param[ 'user' ];
        $pass = $param[ 'pass' ];
        $intID = $param[ 'intID' ];

        try {
            $url = app_config::url_apiallgraduates('staff/interpreter/confirm_login');

            $data = array(
                'user' => $user,
                'pass' => $pass,
                'intID' => $intID
            );

            $result = json_decode(post::call($url, $data), true);

            if (!$result) {
                $message = messageModel::returnMessage(true, 'Invalid login credentials.', null);
                return $response->withJson($message);
            }
            if ($result[ 'error' ]) {
                $message = messageModel::returnMessage(true, $result[ 'message' ]);
                return $response->withJson($message);
            }
            else {
                $message = messageModel::returnMessage(false, 'Success', $result);
                return $response->withJson($message);
            }

        } catch (Exception $e) {
            $message = messageModel::returnMessage(true, $e->getMessage(), null);
            return $response->withJson($message);

        }
    }

}
