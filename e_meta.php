<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Tagwords Meta Handler
 *
 * $Source: /e107_plugins/XpandSlider/e_meta.php,v $
 * $Revision$
 * $Date$
 * $Author$
 *
*/

if (!defined('e107_INIT')) { exit; }

$res = '<!-- XpandSlider CSS -->'.PHP_EOL;
$res .= '<link rel="stylesheet" href="'.e_PLUGIN_ABS.'xpandSlider/css/xpnsld.css" type="text/css" />'.PHP_EOL;
$res .= '<link rel="stylesheet" href="' . e_WEB_ABS . 'js/camera/css/camera.css" type="text/css" />'.PHP_EOL;
$res .= '<!-- /XpandSlider CSS -->'.PHP_EOL;

echo $res;

?>
