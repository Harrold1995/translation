<?php
namespace App\Plugins;

class Ads3
{
    public static function html () {
        ob_start();

        ?>
       
        <ads-3/>

        <?php 
    
        return ob_get_clean();
    }

}
