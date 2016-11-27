<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Custom install/uninstall/update routines for xpanslider plugin
 * *
 */

require_once("conf.php");

if (!defined('e107_INIT'))
{
    require_once("../../class2.php");
}

$xpandSliderPrefs = e107::getPlugPref(XPNSLD_NAME); // get plugin prefs

if (!defined('XPNSLD_DEBUG')) {
    define('XPNSLD_DEBUG', $xpandSliderPrefs['xpnsld_debug']);
}

class xpandslider_setup {

    function install_pre($var)
    {
        //print_a($var);
        // echo "custom install 'pre' function<br /><br />";
    }

    /**
     * For inserting default database content during install after table has been created by the xpandslider_sql.php file.
     */
    function install_post($var)
    {
        $sql = e107::getDb();
        $mes = e107::getMessage();

        $xpndSldExtra = [
            0 => ["captionFx" => "fadeFromRight"],
            1 => ["link" => "//facebook.com/djbeatermusic", "target" => "_blank"],
            2 => ["captionFx" => "moveFromBottom"],
            3 => ["captionFx" => "fadeFromTop"],
        ];

        $xpandSlider = [
            0 => [
                'caption' => 'xpandSlider Caption 1!',
                'content' => '<b>Xpand Slider 1 HTML content!</b>',
                'image' => 'demo/1.jpg',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
                'extra' => json_encode($xpndSldExtra[0]),
                'visibility' => 0,
                'position' => 1
            ],
            1 => [
                'caption' => 'xpandSlider can handle also videos!',
                'content' => '[html]<p><iframe width="100%" height="100%" src="https://www.youtube.com/embed/11Qbbipv8dE" frameborder="0"></iframe></p>[/html]',
                'image' => 'demo/2.jpg',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
                'extra' => json_encode($xpndSldExtra[1]),
                'visibility' => 0,
                'position' => 2
            ],
            2 => [
                'caption' => 'xpandSlider Caption 3!',
                'content' => '
                  <b>Xpand Slider 3 HTML content!</b>
                  <div class="fadeIn camera_effected">Camere line 1</div>
                  <div class="fadeIn camera_effected">Camere line 2</div>
                  <div class="fadeIn camera_effected">Camere line 3</div>
                  <div class="fadeIn camera_effected">Camere line 4</div>
                ',
                'image' => 'demo/3.jpg',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
                'extra' => json_encode($xpndSldExtra[2]),
                'visibility' => 0,
                'position' => 3
            ],
            3 => [
                'caption' => 'xpandSlider Caption 4!',
                'content' => '<b>Xpand Slider 4 HTML content!</b>',
                'image' => 'xpandSlider-na.jpg',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
                'extra' => json_encode($xpndSldExtra[3]),
                'visibility' => 0,
                'position' => 4
            ]
        ];

        foreach ($xpandSlider as $slide) {
            if ($sql->insert(XPNSLD_DB, $slide, false)) {
                $mes->add('Added ' . $slide['caption'], E_MESSAGE_SUCCESS);
            } else {
                $mes->add('Error adding '. $slide['caption'], E_MESSAGE_ERROR);
            }
        }
    }

    function uninstall_options()
    {
        /*
          $listoptions = array(0 => 'option 1', 1 => 'option 2');

          $options = array();
          $options['mypref'] = array(
          'label' => 'Custom Uninstall Label',
          'preview' => 'Preview Area',
          'helpText' => 'Custom Help Text',
          'itemList' => $listoptions,
          'itemDefault' => 1
          );

          return $options;
         *
         */
    }

    function uninstall_post($var)
    {
        //print_a($var);
    }

    function upgrade_post($var)
    {
        // $sql = e107::getDb();
    }
}
