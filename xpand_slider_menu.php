<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Blank Plugin
 *
*/
	
	### conf ###
	
	define("XPN_SLD", "xpandSlider"); // plugin name
	define("XPN_SLD_DIR", "xpandSlider/"); // plugin dir with trailing slash
	define("XPN_SLD_ELFINDER", "plugins/elFinder/php/connector.php?cmd=file&target="); // elfinder path
	define("XPN_SLD_IMG_DIR", "images/slides/"); // images dir
	define("DB_SLIDER", "xpand_slider"); // db table
	
	### end conf ###
	
	if(!defined('e107_INIT')){
		require_once("../../class2.php");
	}

	if(!e107::isInstalled(XPN_SLD)){
		header("location:".e_BASE."index.php");
		exit();
	}
	
	$plp = e107::getPlugPref(XPN_SLD);				// get plugin prefs
	
	define('XPN_SLD_DEBUG', $plp['xpn_sld_debug']);
	//print_r($plp);
	
	if(XPN_SLD_DEBUG == true){
		ini_set('display_errors', 'On');
		error_reporting(E_ALL | E_STRICT);
	}
	
	if(!defined('XPN_SLD_INIT')){
		//require_once('controllers/xpand_slider_class.php');
	}
	
	
	//$plugin = new plugin_xpand_slider_index_controller();
	e107::js(XPN_SLD,'js/jquery.easing.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'js/camera.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'js/xpn-sld-js.js','jquery');	// Load Plugin javascript and include jQuery framework
	
	/*
	e107::js(XPN_SLD,'gridster.js/jquery.gridster.with-extras.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'js/jquery.color.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'gridster.js/jquery.coords.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'gridster.js/jquery.gridster.extras.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'gridster.js/jquery.draggable.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'gridster.js/utils.js','jquery');	// Load Plugin javascript and include jQuery framework
	e107::js(XPN_SLD,'gridster.js/jquery.collision.js','jquery');	// Load Plugin javascript and include jQuery framework
	
	e107::js(XPN_SLD,'nested/makeboxes.js','jquery');	// Load Plugin javascript and include jQuery framework
	
	*/
	
	//e107::css(XPN_SLD,'css/style.css');		// load css file
	
	//e107::css(XPN_SLD,'gridster.js/jquery.gridster.css');		// load css file
	
	e107::lan(XPN_SLD); 					// load language file ie. e107_plugins/_blank/languages/English.php 
	//e107::meta('keywords','some words');	// add meta data to <HEAD> 
	
	//require_once(HEADERF); 					// render the header (everything before the main content area) 
	
	//$plp = e107::getPlugPref();				// iegūt plugin iestatījumus
	$sql = e107::getDB(); 					// mysql class object
	$sql->db_Set_Charset("utf8");
	//$tp = e107::getParser(); 				// parser for converting to HTML and parsing templates etc. 
	//$frm = e107::getForm(); 				// Form element class. 
	$ns = e107::getRender();				// render in theme box. 

	/**
	 * Query and fetch at once
	 * 
	 * Examples:
	 * <code>
	 * <?php
	 * 
	 * // Get single value, $multi and indexField are ignored
	 * $string = e107::getDb()->retrieve('user', 'user_email', 'user_id=1');
	 * 
	 * // Get single row set, $multi and indexField are ignored
	 * $array = e107::getDb()->retrieve('user', 'user_email, user_name', 'user_id=1');
	 * 
	 * // Fetch all, don't append WHERE to the query, index by user_id, noWhere auto detected (string starts with upper case ORDER)
	 * $array = e107::getDb()->retrieve('user', 'user_id, user_email, user_name', 'ORDER BY user_email LIMIT 0,20', true, 'user_id');
	 * 
	 * // Same as above but retrieve() is only used to fetch, not useable for single return value
	 * if(e107::getDb()->select('user', 'user_id, user_email, user_name', 'ORDER BY user_email LIMIT 0,20', true))
	 * {
	 * 		$array = e107::getDb()->retrieve(null, null, null,  true, 'user_id');
	 * }
	 * 
	 * // Using whole query example, in this case default mode is 'single' 
	 * $array = e107::getDb()->retrieve('SELECT  
	 * 	p.*, u.user_email, u.user_name FROM `#user` AS u 
	 * 	LEFT JOIN `#myplug_table` AS p ON p.myplug_table=u.user_id 
	 * 	ORDER BY u.user_email LIMIT 0,20'
	 * );
	 * 
	 * // Using whole query example, multi mode - $fields argument mapped to $multi
	 * $array = e107::getDb()->retrieve('SELECT u.user_email, u.user_name FROM `#user` AS U ORDER BY user_email LIMIT 0,20', true);
	 * 
	 * // Using whole query example, multi mode with index field
	 * $array = e107::getDb()->retrieve('SELECT u.user_email, u.user_name FROM `#user` AS U ORDER BY user_email LIMIT 0,20', null, null, true, 'user_id');
	 * </code>
	 * 
	 * @param string $table if empty, enter fetch only mode
	 * @param string $fields comma separated list of fields or * or single field name (get one); if $fields is of type boolean and $where is not found, $fields overrides $multi
	 * @param string $where WHERE/ORDER/LIMIT etc clause, empty to disable
	 * @param boolean $multi if true, fetch all (multi mode)
	 * @param string $indexField field name to be used for indexing when in multi mode
	 * @param boolean $debug
	 */

	//$iestatijumi = e107::getPlugPref(XPN_SLD); // iegūst plugin iestatījumus
	//print_a($iestatijumi);
	
	if($slides = $sql->retrieve(DB_SLIDER, '*', 'xpand_slider_class IN ('.USERCLASS_LIST.') ORDER BY xpand_slider_order ASC', true)){
		
		shuffle($slides);
		
		$res = '<div class="slider-container"><div class="slides">';
		
		foreach($slides as $key => $value){
			$imgs = explode(',', $value['xpand_slider_imgs']);
			
			$url = urlencode('?cmd=file&target=');
			//$res = '../../thumb.php?src='.e_PLUGIN_ABS.XPN_SLD_DIR.'plugins/elFinder/php/connector.php'.$url.$imgs[1].'&w=500';
			
			$res .= '<div class="slide" data-slide="'.$value['xpand_slider_id'].'" data-src="'.e_PLUGIN_ABS.XPN_SLD_DIR.XPN_SLD_ELFINDER.$imgs[1].'" data-thumb="'.e_PLUGIN_ABS.XPN_SLD_DIR.XPN_SLD_IMG_DIR.$imgs[0].'"><div class="camera_caption">'.$value['xpand_slider_title'].'</div><div class="fadeIn camera_effected">'.$value['xpand_slider_content'].'</div><img src="'.e_PLUGIN_ABS.XPN_SLD_DIR.XPN_SLD_ELFINDER.$imgs[1].'" data-tmb="'.$imgs[0].'" /></div>';
		}
		
		$res .= '</div></div>';
		
		
		/**
		* Converts the text (presumably retrieved from the database) for HTML output.
		*
		* @param string $html
		* @param boolean $parseBB [optional]
		* @param string $modifiers [optional] TITLE|SUMMARY|DESCRIPTION|BODY|RAW|LINKTEXT etc.
		*                Comma-separated list, no spaces allowed
		*                first modifier must be a CONTEXT modifier, in UPPER CASE.
		*                subsequent modifiers are lower case - see $this->e_Modifiers for possible values
		* @param mixed $postID [optional]
		* @param boolean $wrap [optional]
		* @return string
		* @todo complete the documentation of this essential method
		*/
		//public function toHTML($html, $parseBB = FALSE, $modifiers = '', $postID = '', $wrap = FALSE)
		
		$html = $tp->toHtml($res, true);
	}
	
	echo $html
	
	//$ns->tablerender(LAN_PLUG_XPN_SLD_MENU_NAME, $res);
	
?>