<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * blankd under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * xpandSlider plugin
 *
 * $Source: /e107_plugins/xpandslider/xpandslider_admin.php,v $
 * $Revision$
 * $Date$
 * $Author$
 *
 */

require_once("conf.php");

//$eplug_admin = true;

if (!e107::isInstalled(XPNSLD_NAME))
{
    e107::redirect('admin');
    exit;
}

e107::lan(XPNSLD_NAME, 'global'); // Loads e_PLUGIN."xpandslider/languages/English/global.php (if English is the current language)
e107::lan(XPNSLD_NAME, 'admin');
e107::css('core', 'elfinder/css/elfinder.min.css');
e107::css('core', 'elfinder/css/theme.css');

e107::css('url', '//cdn.jsdelivr.net/brutusin.json-forms/1.4.0/css/brutusin-json-forms.min.css');
e107::js('url', '//cdn.jsdelivr.net/brutusin.json-forms/1.4.0/js/brutusin-json-forms.min.js');
//e107::js('url', '//cdn.jsdelivr.net/brutusin.json-forms/1.4.0/js/brutusin-json-forms-bootstrap.min.js');

e107::js('core', 'elfinder/js/elfinder.min.js');
e107::js(XPNSLD_NAME, 'js/xpn-sld-admin-js.js', 'jquery');

new plugin_xpandslider_admin;

require_once(e_ADMIN . "auth.php");
//includes/admin.php is auto-loaded.

// Send page content
e107::getAdminUI()->runPage();

require_once(e_ADMIN . "footer.php");
