<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2018 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * xpandSlider plugin - Perfect responsive image, HTML slider for e107 CMS
 * Author: rolla <raitis.rolis@gmail.com>
 *
*/

if (!defined('e107_INIT')) {
    exit;
}

require_once("conf.php");

if (!defined('e107_INIT')) {
    require_once("../../class2.php");
}

$xpandSliderPrefs = e107::getPlugPref(XPNSLD_NAME); // get plugin prefs

if (!defined('XPNSLD_DEBUG')) {
    define('XPNSLD_DEBUG', $xpandSliderPrefs['xpnsld_debug']);
}

$e107Config = e107::getPref();

$text = '<div class="xpnsld">
           <div class="logo-help">
             <div class="logo-help-inner pull-left">
               <img
                 src="' . e_PLUGIN_DIR . 'images/xpn_sld_32.png"
                 class="xpnsld-logo-help"
                 title="' . XPNSLD_TITLE . ' v' . $e107Config['plug_installed'][e_CURRENT_PLUGIN] . '">
             </div>
             <span class="badge badge-info pull-left">
               v' . $e107Config['plug_installed'][e_CURRENT_PLUGIN] . '
             </span>
           </div>
         </div>';

// PHP Regex to Remove http:// and https:// from string
$siteurl = preg_replace('#^https?://#', '', e107::getConfig()->getPref('siteurl'));
$siteurl = rtrim($siteurl, '/');

$crTitle = XPNSLD_TITLE . ' v' . $e107Config['plug_installed'][e_CURRENT_PLUGIN] . ' ' . LAN_PLUG_XPNSLD_CR_INFO;

$text .= '<div class="xpnsld">
	    <div class="footer-content">
              <img
                class="footer-img"
                src="images/help/urdt-cr-logo.png"
                title="' . $crTitle . '">
	      <div class="footer-cr">
	        <div class="footer-cr-text">
	          <a
                    href="http://projects.urdt.lv/web/' . e_CURRENT_PLUGIN . '?bannerclient=' . $siteurl . '"
                    target="_blank"
                    title="' . $crTitle . '">
	            <p>© ' . XPNSLD_START_YEAR . ' - ' . XPNSLD_LAST_YEAR . ' ' . XPNSLD_URDT_VERSION . '</p>
	          </a>
	      </div>
	    </div>
	  </div>
        </div>';

if ($text) {
    $ns->tablerender($title, $text);
}
