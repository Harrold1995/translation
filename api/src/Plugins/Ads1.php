<?php
namespace App\Plugins;

class Ads1
{
    public static function html () {
        ob_start();
        ?>

        <ads-1/>

        <?php 
    
        return ob_get_clean();
    }

}
