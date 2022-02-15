<?php
namespace App\Plugins;

class FatPlugin
{
    public static function html () {
        ob_start();

        ?>
		
		<fat-plugin ref="FatPlugin"/>

        <?php 
    
        return ob_get_clean();
    }

}
