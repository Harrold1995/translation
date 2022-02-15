<?php
namespace App\Controllers\CMS;

use Slim\Http\Request;
use Slim\Http\Response;

class BaseController
{
   protected $container;
   public function __construct($container){
       $this->container = $container;
   }

   public function jsonResponse(Response $response, string $status, $message, int $code)
   {
       $result = [
           'code' => $code,
           'status' => $status,
           'message' => $message,
       ];

       return $response->withJson($result, $code, JSON_PRETTY_PRINT);
   }
}
