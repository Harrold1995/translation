<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 5/9/18
 * Time: 11:48 AM
 */

// namespace API\curl;

namespace App\Services\curl;


class get extends base
{

    public function __construct ($url)
    {
        parent::__construct($url);
    }

    public static function call ($url, array $headers = null)
    {
        $s = new self($url);
        if ($headers) $s->setHeaders($headers);
        return $s->process();
    }
}