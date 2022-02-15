<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/11/16
 * Time: 2:45 PM
 */

namespace API\jwt;

use Firebase\JWT\JWT;
use Slim\Http\Response;
use Slim\Http\Request;
use Slim\Container;
use API\interpreter\account;

class token
{
    /* @var $container Container */
    private $container;

    function __construct (Container $app = null)
    {
        $this->container = $app;
    }

    public static function tokenEncode ($token)
    {
        return JWT::encode($token, self::key(), self::algorithm());
    }

    public static function tokenDecode ($token)
    {
        $decoded = JWT::decode($token, self::key(), [self::algorithm()]);
    }

    /**
     * Middleware class to generate and return a JWT token
     *
     * @param Request $request
     * @param Response $response
     * @param          $next
     *
     * @return Response
     */

    public function validate (Request $request, Response $response, $next)
    {
        $token = @ $request->getHeader('HTTP_BEARER')[ 0 ];
        if (!$token) $token = $request->getQueryParam('token');
        if (!$token) $token = $request->getParsedBodyParam('token');

        try {

            try {
                $decoded = JWT::decode($token, self::key(), [self::algorithm()]);
            } catch (\Exception $e) {
                if ($e->getMessage() == "Wrong number of segments") {
                    throw new \Exception($e->getMessage());
                }
                $response = $response->withJson((object)['error' => $e->getMessage()], 401);
                return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Bearer')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ;
            }

            if ($decoded->data->staffid || $decoded->data->intid || $decoded->data->contactid) {
                $request = $request->withAttribute('data', $decoded->data);
                $response = $next($request, $response);
            }
            else {
                $response = $response->withJson((object)['error' => 'Invalid Token'], 401);
                return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Bearer')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ;
            }
        } catch (\Exception $e) {
            $name = $request->getAttribute('route')->getName();
            if ($name == 'login') {
                $account = new account();
                return $account->login($request, $response);
            }

            if ($name == 'hello') {
                $response = $next($request, $response);
            }
            else {
                $response = $response->withJson((object)['error' => $e->getMessage()], 424);
            }
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Bearer')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ;
    }

    public static function key ()
    {
        return 'T/Svb.&{*Ivqb:ccXV,Spxj3qpy49[A';
    }

    public static function algorithm ()
    {
        return 'HS512';
    }

    public static function getToken ($data, $expires = '+30 days')
    {
        $token = [
            "iss" => "api.allgraduates.com.au",
            'exp' => strtotime($expires),
            'iat' => time(),
            'data' => []
        ];

        foreach ($data as $key => $item) {
            $token[ 'data' ][ $key ] = $item;
        }

        return JWT::encode($token, self::key(), self::algorithm());

    }

    public static function checkJobBookingToken ($token)
    {
        try {
            $decoded = JWT::decode($token, self::key(), [self::algorithm()]);
            if (isset($decoded->data)) {
                if ($decoded->data->job_id > 0 && $decoded->data->interpreter_id != null) {
                    $response = array("job_id" => $decoded->data->job_id, "interpreter_id" => $decoded->data->interpreter_id, "error" => false);
                }
                else {
                    $response = array("job_id" => null, "interpreter_id" => null, "error" => true);
                }
            }
            else {
                $response = array("job_id" => null, "interpreter_id" => null, "error" => true);
            }
        } catch (\Exception $e) {
            $response = array("job_id" => null, "interpreter_id" => null, "error" => $e->getMessage());
        }
        return $response;
    }

    public static function checkPasswordToken ($token)
    {
        try {
            $decoded = JWT::decode($token, self::key(), [self::algorithm()]);

            //$request = $request->withAttribute( 'data' , $decoded->data );
            if (isset($decoded->data)) {
                if ($decoded->data->contactid) {
                    $response = 1;
                }
                else {
                    if ($decoded->data->intid) {
                        $response = 1;
                    }
                    else {
                        $response = 0;
                    }
                }
            }
            else {
                $response = 0;
            }
        } catch (\Exception $e) {
            $response = 0;
        }
        return $response;
    }
}
