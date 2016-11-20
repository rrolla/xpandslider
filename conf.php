<?php

if (!defined('e107_INIT'))
{
    require_once("../../class2.php");
}

define("XPNSLD_TITLE", "xpandSlider"); // plugin title
define("XPNSLD_NAME", "xpandslider"); // plugin name aka path
define("XPNSLD_DIR", "xpandslider/"); // plugin dir with trailing slash
define("XPNSLD_IMG_DIR", "images/slides/"); // slides dir with trailing slash
define("XPNSLD_DB", "xpandslider"); // db table
define("XPNSLD_START_YEAR", 2013); // plugin first release year
define("XPNSLD_LAST_YEAR", 2016); // plugin last edit year

$xpandSliderPrefs = e107::getPlugPref(XPNSLD_NAME); // get plugin prefs

define('XPNSLD_DEBUG', $xpandSliderPrefs['xpnsld_debug']);
//print_r($plp);
