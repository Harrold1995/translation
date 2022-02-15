<?php

// namespace API\curl;

namespace App\Services\curl;

class post extends base
{
    public function __construct ($url)
    {
        parent::__construct($url);
    }

    public static function call ($url, $data, array $headers = null)
    {
        $s = new self($url);
        if ($headers) $s->setHeaders($headers);
        $s->opt(CURLOPT_POSTFIELDS, $data);
        return $s->process();
    }
}