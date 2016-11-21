<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 xpandslider Plugin
 *
 */

require_once("conf.php");

if (XPNSLD_DEBUG == true)
{
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}

e107::lan(XPNSLD_NAME, 'global'); // load language file 107_plugins/xpandslider/languages/English/global.php

$text = e107::getParser()->parseTemplate("{XPANDSLIDER}");
echo $text;
