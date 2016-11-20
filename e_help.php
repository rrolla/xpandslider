<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: e107_plugins/xpandContact/e_help.php,v $
 * $Revision$
 * $Date$
 * $Author$
 */

if (!defined('e107_INIT'))
{
    exit;
}

$plp = e107::getPref(); // iegūt core iestatījumus

$title = '<div class="xpn-cnt-logo-help">
            <div class="xpn-cnt-logo-help-inner">
              <img src="' . e_PLUGIN_DIR . 'images/xpn_sld_32.png" class="xpnsld-logo-help" title="' . XPNSLD_TITLE . '">
            </div>
          </div>';
$text = '<span class="label label-info">
          ' . XPNSLD_TITLE . '
         </span>
         <span class="badge badge-info">
           v' . $plp['plug_installed'][e_CURRENT_PLUGIN] . '
         </span>';

$siteurl = e107::getConfig()->getPref('siteurl');
// PHP Regex to Remove http:// from string That will work for both http:// and https://
$siteurl = preg_replace('#^https?://#', '', $siteurl);
$siteurl = rtrim($siteurl, '/');

//echo '<pre>';
//print_r( e107::getConfig() );

$text .= '
	<div class="footer-content">
          <img class="footer-img" src="images/help/urdt-cr-logo.png" title="' . XPNSLD_TITLE . ' v' . $plp['plug_installed'][e_CURRENT_PLUGIN] . ' ' . LAN_PLUG_XPNSLD_CR_INFO . '">	
	  <div class="footer-cr">
	    <div class="footer-cr-text">
	      <a href="//projects.urdt.lv/xpandcontact?bannerclient=' . $siteurl . '" target="_blank" title="' . XPNSLD_TITLE . ' v' . $plp['plug_installed'][e_CURRENT_PLUGIN] . ' ' . LAN_PLUG_XPNSLD_CR_INFO . '">
	        <p>© 2014 <br />Universal Radio DJ Team v4.0</p>
	      </a>
	    </div>
	  </div>
	</div>';

if ($text)
{
    $ns->tablerender($title, $text);
}