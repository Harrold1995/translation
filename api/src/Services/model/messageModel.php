<?php
/**
 * Created by PhpStorm.
 * User: satish
 * Date: 14/12/18
 * Time: 4:09 PM
 */

namespace API\model;

class messageModel
{
    public static function returnMessage ($error = true, $message = null, $data = null)
    {
        return (object)['error' => $error, 'message' => $message, 'data' => $data];
    }
}