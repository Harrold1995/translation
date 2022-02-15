<?php
namespace App\Plugins;



class TrustedSource
{
    public static function html () {
        ob_start();
      ?>
        <footer-carousel/>
        
      <?php 
      return ob_get_clean();
    }

}
