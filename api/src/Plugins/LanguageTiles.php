<?php
namespace App\Plugins;


use App\Models\Language;


class LanguageTiles
{
    public static function html () {
        ob_start();
        $languages = Language::where('include_in_tiles',1)->where('id','<>',256)->orderBy('language')->get();
      ?>

      <div class="row row-eq-height">

          <div class="col-6 col-sm-3 col-md-5th col-lg-7th q-mb-lg language-card">
            <a class="curve_corners d-flex q-pt-md q-pb-md q-pa-lg text-center h-100 box_shdw active" href="/en/home"><span class="my-auto mx-auto">English</span></a>
          </div>

        <?php  foreach ($languages as $value): 
            $href = '/'.$value->word_press_code.'/home';
        ?>

          <div class="col-6 col-sm-3 col-md-5th col-lg-7th q-mb-lg language-card">
            <a class="curve_corners d-flex q-pt-md q-pb-md q-pa-lg text-center h-100 box_shdw" href="<?php echo $href; ?>">
              <span class="my-auto mx-auto">
                <?php echo $value->language.' - '.$value->native_name ?>
              </span>
            </a>
          </div>

        <?php endforeach; ?>
      </div>
      <div class="clearfix"></div>
      <?php 
      return ob_get_clean();
    }

}
