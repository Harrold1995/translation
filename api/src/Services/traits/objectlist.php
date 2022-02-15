<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 6/2/17
 * Time: 5:28 PM
 */

namespace API\traits;

use API\functions\debug;
use Slim\Http\Response;


trait objectlist
{
    function getList (Response $response)
    {
        $this->select();
        $response = $response->withJson($this->rows);
        return $response;
    }
}
