<?php
namespace App\Plugins;

class Ads2
{
    public static function html () {
        ob_start();

        ?>

        <ads-2/>


        <?php 
    
        return ob_get_clean();
    }

}
