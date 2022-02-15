<?php
/**
 * Created by PhpStorm.
 * User: satish
 * Date: 17/7/19
 * Time: 11:06 AM
 */

namespace API\config;

class app_config
{
    public static $name = "satish";

    public static function url_apiallgraduates ($url = null)
    {

        switch ($_SERVER[ 'SERVER_ADDR' ]) {
            case "10.64.7.194":
                return 'https://api.allgraduates.com.au/' . $url;
            case "192.168.100.206":
                return 'http://apidev.allgraduates.com.au/' . $url;
            case "127.0.0.1":
                return 'http://127.0.0.1:8888/api.allgraduates.com.au/public/' . $url;
            case "::1":
                return 'http://127.0.0.1:8888/api.allgraduates.com.au/public/' . $url;
        }
    }
}
