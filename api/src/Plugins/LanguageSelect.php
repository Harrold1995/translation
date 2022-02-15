<?php
namespace App\Plugins;

use App\Models\Language;


class LanguageSelect
{
    public static function html () {
        ob_start();
        $languages = Language::where('include_in_dropdown',1)->where('id','<>',256)->orderBy('language')->get();
        ?>
        <form class="lng-slctn" action="/">
            <select name="lang">
                <option value="">Select Language</option>
                <option selected="selected" value="/en/home">English</option>

                <?php  foreach ($languages as $value): ?>
                    <option value="/home">
                        <?php echo $value->language.' - '.$value->native_name ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input class="float-right btn" type="submit" value="CONTINUE">
        </form>
        <?php 
        return ob_get_clean();
    }

}
