<?php
namespace App\Plugins;

class AdsCarousel
{
    public static function html () {
        ob_start();
        ?>
        
        <ads-carousel/>

        <?php 
        return ob_get_clean();
    }

}
