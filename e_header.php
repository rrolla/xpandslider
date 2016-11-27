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

if (USER_AREA) // prevents inclusion of JS/CSS/meta in the admin area.
{   e107::css('xpandslider', 'css/xpnsld.css');
    //e107::css('core', 'camera-slideshow/css/camera.css');
    //e107::js('core', 'camera-slideshow/scripts/jquery.easing.1.3.js');
    //e107::js('core', 'camera-slideshow/scripts/camera.min.js');
    //e107::js('core', 'camera-slideshow/scripts/jquery.mobile.customized.min.js');
    e107::css('url', '//cdnjs.cloudflare.com/ajax/libs/Camera/1.3.4/css/camera.min.css');
    e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/Camera/1.3.4/scripts/camera.min.js');
    e107::js('url', '//cdnjs.cloudflare.com/ajax/libs/Camera/1.3.4/scripts/jquery.mobile.customized.min.js');
}