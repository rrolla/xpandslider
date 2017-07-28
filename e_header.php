<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2014 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Related configuration module - News
 *
 *
*/

if (!defined('e107_INIT')) { exit; }

$scriptLoad = 'cdn';
$min = true;
$cameraV = '1.3.4';
$jqueryV = '1.4.1';

if (defined(XPNSLD_DEBUG) &&  XPNSLD_DEBUG == true)
{
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
    $min = false;
}


$minLoad = $min ? '.min' : '';

if (USER_AREA) // prevents inclusion of JS/CSS/meta in the admin area.
{   e107::css('xpandslider', 'css/xpnsld.css');

    if ($scriptLoad != 'cdn') {
        e107::css('core', '../lib/camera-slideshow/css/camera.css');
        e107::js('core', '../lib/camera-slideshow/scripts/jquery.easing.1.3.js');
        e107::js('core', '../lib/camera-slideshow/scripts/camera.min.js');
        //e107::js('core', '../lib/camera-slideshow/scripts/jquery.mobile.customized.min.js');
    }

    if ($scriptLoad == 'cdn') {
        e107::css('url', '//cdnjs.cloudflare.com/ajax/libs/Camera/' . $cameraV . '/css/camera' . $minLoad . '.css');

        e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/' . $jqueryV . '/jquery-migrate' . $minLoad . '.js');
        e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/Camera/' . $cameraV . '/scripts/camera' . $minLoad . '.js');
        e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/' . $jqueryV . '/jquery.easing' . $minLoad . '.js');
        e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/' . $jqueryV . '/jquery.easing.compatibility' . $minLoad . '.js');
        e107::js('xpandslider', 'js/jquery.mobile.customized.min.js', 'jquery');
        //e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/Camera/' . $cameraV . '/scripts/jquery.mobile.customized.min.js');
        //e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.4.5/jquery.mobile' . $minLoad . '.js', 'jquery', 0);
    }
}
