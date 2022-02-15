<?php
namespace App\Plugins;

use App\Plugins\LanguageTiles;
use App\Plugins\LanguageSelect;
use App\Plugins\TrustedSource;
use App\Plugins\FatPlugin;
use App\Plugins\AdsCarousel;
use App\Plugins\Ads1;
use App\Plugins\Ads2;
use App\Plugins\Ads3;


class Plugin
{
   
    public static function language_tiles () {
        return LanguageTiles::html();
    }

    public static function language_select () {
        return LanguageSelect::html();
    }

    public static function trusted_source () {
        return TrustedSource::html();
    }

    public static function plugin () {
        return FatPlugin::html();
    }

    public static function add_carousel () {
        return AdsCarousel::html();
    }


    public static function add_pos_1 () {
        return Ads1::html();
    }

    public static function add_pos_2 () {
        return Ads2::html();
    }

    public static function add_pos_3 () {
        return Ads3::html();
    }




}
