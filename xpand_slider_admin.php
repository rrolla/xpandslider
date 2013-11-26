<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * blankd under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 blank Plugin
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/blank/admin_config.php,v $
 * $Revision$
 * $Date$
 * $Author$
 *
*/

	
	
	### konfigurācija ###
	
	define("LA_KAT", "la_katalogs"); // plugin nosaukums
	define("LA_KAT_DIR", "la_katalogs/"); // plugin dir ar trailing slash
	
	define("PLUGIN_PR_DIREKTORIJA", "images/preces/"); // preču bilžu direktorija
	define("PLUGIN_KAT_DIREKTORIJA", "images/kategorijas/"); // kategoriju bilžu direktorija
	define("PLUGIN_RAZ_DIREKTORIJA", "images/razotaji/"); // ražotāju bilžu direktorija

	define("DB_KATALOGS", "la_kat_preces"); // datubazes nosaukums
	define("DB_KATEGORIJAS", "la_kat_kategorijas"); // datubazes nosaukums
	define("DB_RAZOTAJI", "la_kat_razotaji"); // datubazes nosaukums
	
	$eplug_admin = true;
	define('LA_KATALOGS_DEBUG', TRUE);

	//echo PLUGIN_DIR."/bildites";

	### beidzas konfigurācija ###

	require_once("../../class2.php");
	if(!getperms("P") || !plugInstalled(LA_KAT)){
		header("location:".e_BASE."index.php");
		exit() ;
	}

	include_lan(e_PLUGIN.LA_KAT_DIR.'languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_admin.php');
	//include_lan(e_PLUGIN.LA_KAT_DIR.'languages/'.e_LANGUAGE.'/admin_download.php');
	//require_once(e_PLUGIN.'download/handlers/adminDownload_class.php');
	//require_once(e_PLUGIN.LA_KAT_DIR.'handlers/download_class.php');
	
	require_once(e_PLUGIN.LA_KAT_DIR.'controllers/la_katalogs_adm_pre_class.php');
	require_once(e_PLUGIN.LA_KAT_DIR.'controllers/la_katalogs_adm_kat_class.php');
	require_once(e_PLUGIN.LA_KAT_DIR.'controllers/la_katalogs_adm_raz_class.php');
	require_once(e_PLUGIN.LA_KAT_DIR.'controllers/la_katalogs_adm_diag_class.php');
	
	e107::css(LA_KAT,'datnes/jquery/ui-themes/smoothness/jquery-ui-1.8.18.custom.css');
	e107::css(LA_KAT,'css/admin.css');
	e107::css(LA_KAT,'datnes/css/common.css');
	e107::css(LA_KAT,'datnes/css/dialog.css');
	e107::css(LA_KAT,'datnes/css/toolbar.css');
	e107::css(LA_KAT,'datnes/css/navbar.css');
	e107::css(LA_KAT,'datnes/css/statusbar.css');
	e107::css(LA_KAT,'datnes/css/contextmenu.css');
	e107::css(LA_KAT,'datnes/css/cwd.css');
	e107::css(LA_KAT,'datnes/css/quicklook.css');
	e107::css(LA_KAT,'datnes/css/commands.css');
	e107::css(LA_KAT,'datnes/css/theme.css');
	
	e107::js(LA_KAT,'datnes/js/elFinder.js');
	e107::js(LA_KAT,'datnes/js/elFinder.version.js');
	e107::js(LA_KAT,'datnes/js/jquery.elfinder.js');
	e107::js(LA_KAT,'datnes/js/elFinder.resources.js');
	e107::js(LA_KAT,'datnes/js/elFinder.options.js');
	e107::js(LA_KAT,'datnes/js/elFinder.history.js');
	e107::js(LA_KAT,'datnes/js/elFinder.command.js');
	e107::js(LA_KAT,'datnes/js/elFinder.history.js');
	
	e107::js(LA_KAT,'datnes/js/ui/overlay.js');
	e107::js(LA_KAT,'datnes/js/ui/workzone.js');
	e107::js(LA_KAT,'datnes/js/ui/navbar.js');
	e107::js(LA_KAT,'datnes/js/ui/dialog.js');
	e107::js(LA_KAT,'datnes/js/ui/tree.js');
	e107::js(LA_KAT,'datnes/js/ui/cwd.js');
	e107::js(LA_KAT,'datnes/js/ui/toolbar.js');
	e107::js(LA_KAT,'datnes/js/ui/button.js');
	e107::js(LA_KAT,'datnes/js/ui/uploadButton.js');
	e107::js(LA_KAT,'datnes/js/ui/viewbutton.js');
	e107::js(LA_KAT,'datnes/js/ui/searchbutton.js');
	e107::js(LA_KAT,'datnes/js/ui/sortbutton.js');
	e107::js(LA_KAT,'datnes/js/ui/panel.js');
	e107::js(LA_KAT,'datnes/js/ui/contextmenu.js');
	e107::js(LA_KAT,'datnes/js/ui/path.js');
	e107::js(LA_KAT,'datnes/js/ui/stat.js');
	e107::js(LA_KAT,'datnes/js/ui/places.js');
	
	e107::js(LA_KAT,'datnes/js/commands/back.js');
	e107::js(LA_KAT,'datnes/js/commands/forward.js');
	e107::js(LA_KAT,'datnes/js/commands/reload.js');
	e107::js(LA_KAT,'datnes/js/commands/up.js');
	e107::js(LA_KAT,'datnes/js/commands/home.js');
	e107::js(LA_KAT,'datnes/js/commands/copy.js');
	e107::js(LA_KAT,'datnes/js/commands/cut.js');
	e107::js(LA_KAT,'datnes/js/commands/paste.js');
	e107::js(LA_KAT,'datnes/js/commands/open.js');
	e107::js(LA_KAT,'datnes/js/commands/rm.js');
	e107::js(LA_KAT,'datnes/js/commands/info.js');
	e107::js(LA_KAT,'datnes/js/commands/duplicate.js');
	e107::js(LA_KAT,'datnes/js/commands/rename.js');
	e107::js(LA_KAT,'datnes/js/commands/help.js');
	e107::js(LA_KAT,'datnes/js/commands/getfile.js');
	e107::js(LA_KAT,'datnes/js/commands/mkdir.js');
	e107::js(LA_KAT,'datnes/js/commands/mkfile.js');
	e107::js(LA_KAT,'datnes/js/commands/upload.js');
	e107::js(LA_KAT,'datnes/js/commands/download.js');
	e107::js(LA_KAT,'datnes/js/commands/edit.js');
	e107::js(LA_KAT,'datnes/js/commands/quicklook.js');
	e107::js(LA_KAT,'datnes/js/commands/quicklook.plugins.js');
	e107::js(LA_KAT,'datnes/js/commands/extract.js');
	e107::js(LA_KAT,'datnes/js/commands/archive.js');
	e107::js(LA_KAT,'datnes/js/commands/search.js');
	e107::js(LA_KAT,'datnes/js/commands/view.js');
	e107::js(LA_KAT,'datnes/js/commands/resize.js');
	e107::js(LA_KAT,'datnes/js/commands/sort.js');
	
	e107::js(LA_KAT,'datnes/js/i18n/elfinder.en.js');
	
	e107::js(LA_KAT,'datnes/js/jquery.dialogelfinder.js');
	e107::js(LA_KAT,'datnes/js/proxy/elFinderSupportVer1.js');
	
	
	e107::js('url','http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js');
	e107::js('url','http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js');
	e107::js(LA_KAT,'/js/jquery.seourl.js', 'jquery');	// Load Plugin javascript and include jQuery framework

	e107::js('inline', '
		var arrM = []; // masīvs priekš mazām bildēm
		var arrL = []; // masīvs priekš lielām bildēm
		
		function isEmpty(arr){
			if(typeof arr !== "undefined" && arr.length > 0){
				return false;
			}else{
				return true;
			}
		}
		
		// Savieno visas masīva arrM vērtības un ieliek iekš input vērtības
		function pievienoMval(arrM){
			var jver = arrM.join();
			var merkisM = $("input[id$=\"bilde-m\"]");
			merkisM.val(jver);
		}
		
		// Savieno visas masīva arrL vērtības un ieliek iekš input vērtības
		function pievienoLval(arrL){
			var jver = arrL.join();
			var merkisL = $("input[id$=\"bilde-l\"]");
			merkisL.val(jver);
		}
		
		// atrod mazās bildes no input vērtības
		function atrMbildes(force){
			//console.debug("force ir: "+force);
			
			// force is optional
			// the null value would be the default value
			if(!force) force=false;
			
			var merkisM = $("input[id$=\"bilde-m\"]");
			
			if(merkisM.length){
			
				//console.debug(merkisM);
				
				var bildeM = merkisM.val();
				
				if(!bildeM){
					merkisM.val("preces/.tmb/l1_dHVrc3MuanBn1382380393.png");
				}
				
				var bildeM = merkisM.val();
				
				if(force){
					arrM = bildeM.split(",");
					//console.debug(arrm);
					return arrM;
				}else if(!isEmpty(arrM)){
					//console.debug("masiva jau kaut kas ir!");
					return false;
				}else{
					arrM = bildeM.split(",");
					return arrM;
				}
			
			}
		}
		
		// atrod lielās bildes no input vērtības
		function atrLbildes(force){
			//console.debug("force ir: "+force);
			
			// force is optional
			// the null value would be the default value
			if(!force) force=false;
			
			var merkisL = $("input[id$=\"bilde-l\"]");
			
			//console.debug(merkisL.length);
			
			if(merkisL.length){
			
			//console.debug(merkisL);
			
				var bildeL = merkisL.val();
				
				if(!bildeL){
					merkisL.val("l1_dHVrc3MuanBn");
				}
				
				var bildeL = merkisL.val();
				
				if(force){
					arrL = bildeL.split(",");
					//console.debug(arrl);
					return arrL;
				}else if(!isEmpty(arrL)){
					//console.debug("masiva jau kaut kas ir!");
					return false;
				}else{
					arrL = bildeL.split(",");
					return arrL;
				}
			
			}
		}
		
		// funkcija, kas ģenerē bildes no arrM
		function genMbildes(){
			var merkisM = $("input[id$=\"bilde-m\"]");
			$("#elfinder").remove();
			$(".debug").remove();
			$("<div id=\"elfinder\"></div>").insertAfter(merkisM);
			arrM = atrMbildes(true);
			var file = atrLbildes(true);
			
			if(arrM){
			
			$.each(arrM, function(key, val){
				$("#elfinder").append("<div class=\"bildite\" data-bildite=\""+key+"\"><div class=\"img-opt\"><div class=\"img-del\" title=\"'.LAN_PLUGIN_LA_KAT_PR_DELETE.'\"><i class=\"S16 e-delete-16\"></i></div></div><img src=\"'.e_PLUGIN_ABS.LA_KAT_DIR.'images/"+val+"\" data-hash=\""+file[key]+"\"  width=\"200\" data-toggle=\"tooltip\" title=\"'.LAN_PLUGIN_LA_KAT_PR_HELP_BILDE.'\" /></div>");
				$("#elfinder");
			});
			
			var bildite = $("div.bildite").first();
			
			$("<div class=\"bildite-add\" data-bildite=\"add\"><div class=\"img-opt\"><div class=\"img-add btn btn-success\" title=\"'.LAN_PLUGIN_LA_KAT_PR_ADD.'\">'.LAN_PLUGIN_LA_KAT_PR_ADD.'<i class=\"S16 e-add-16\"></i></div></div></div>").insertBefore(bildite);
			
			
			//$("<div class=\"debug\"><button class=\"parM\">kas Masīvā?</button><button class=\"parI\">kas inputā?</button></div>").insertAfter(merkisM);
			
			}
			
		}
		
		// funkcija, kas sakārto bildes iekš arrM
		function sakMbildes(){
			var bildites = $("#elfinder div.bildite");
			var bildes = [];
			bildites.each(function(e){
				var re = "'.e_PLUGIN_ABS.LA_KAT_DIR.'images/";
				var tmb = $(this).find("img").attr("src");
				var bilde = tmb.replace(re, "");
				bildes.push(bilde);
			});
			pievienoMval(bildes);
		}
		
		// funkcija, kas sakārto bildes iekš arrL
		function sakLbildes(){
			var bildites = $("#elfinder div.bildite");
			var bildes = [];
			bildites.each(function(e){
				var hash = $(this).find("img").data("hash");
				bildes.push(hash);
			});
			pievienoLval(bildes);
		}
	
		function pievBildes(files){
			arrM = atrMbildes(true);
			arrL = atrLbildes(true);
			var bildMidx = $("div.bildite").last().data("bildite");
			bildMidx++;
			
			//console.log("masivs: " +arrm);
			//console.log("len: " +len);
			//console.log( "len: " +bilindex );
			
			$.each(files, function(key, val){
				//console.log(key);
				//console.log(files);
				var file = val.hash;
				var re = "images/";
				var tmb = val.tmb;
				tmb = tmb.replace(re, "");
				
				arrM.splice(bildMidx, 0, tmb);
				arrL.splice(bildMidx, 0, file);
				
				pievienoMval(arrM);
				pievienoLval(arrL);
				
				$("#elfinder").append("<div class=\"bildite\" data-bildite=\""+(bildMidx)+"\"><div class=\"img-opt\"><div class=\"img-del\" title=\"'.LAN_PLUGIN_LA_KAT_PR_DELETE.'\"><i class=\"S16 e-delete-16\"></i></div></div><img src=\"'.e_PLUGIN_ABS.LA_KAT_DIR.'images/"+tmb+"\" data-hash=\""+file+"\" width=\"200\"  data-toggle=\"tooltip\" title=\"'.LAN_PLUGIN_LA_KAT_PR_HELP_BILDE.'\" /></div>");
				$("#elfinder");
				
				bildMidx++;
			});
			
			sortOn();
		}
		
		// for debug
	
		$(document).on("click", ".parM", function(){
			console.debug("tmbs: "+arrM);
			console.debug("imgs: "+arrL);
			var lenM = arrM.length;
			var lenL = arrL.length;
			console.debug("tmbs len: "+lenM);
			console.debug("imgs len: "+lenL);
			//console.debug( atrMbildes() );
			return false;
		});
	
		$(document).on("click", ".parI", function(){
			var merkisM = $("input[id$=\"bilde-m\"]");
			var merkisL = $("input[id$=\"bilde-l\"]");
			merkisM = merkisM.val();
			merkisL = merkisL.val();
			console.log("tmbs input: "+merkisM);
			console.log("imgs input: "+merkisL);
			return false;
		});
		
		// end for debug
	
	
		$(document).on("click", "div.img-opt div.img-del", function(){
			if(isEmpty(arrM)){
				arrM = atrMbildes(true);
			}
			
			if(isEmpty(arrL)){
				arrL = atrLbildes(true);
			}
			
			var lenM = arrM.length;
			var lenL = arrL.length;
			
			var bildite = $(this).closest("div.bildite");
			var bildMidx = bildite.data("bildite");
			
			console.log("izdzēsu no arrM: " +arrM[bildMidx]);
			console.log("izdzēsu no arrL: " +arrL[bildMidx]);
			
			//console.log("len: " +len);
			//console.log( "index: " +bilindex );
		
			//var index = $.inArray(bilindex,arrm);
			
			if(lenM != 1){
				arrM.splice(bildMidx, 1);
				arrL.splice(bildMidx, 1);
				pievienoMval(arrM);
				pievienoLval(arrL);
			}else{
				console.debug("viena bilde palikus");
				arrM.splice(bildMidx, 1);
				pievienoMval(arrM);
				genMbildes();
				arrL.splice(bildMidx, 1);
				pievienoLval(arrL);
				atrLbildes();
			}
			bildite.fadeOut("fast", function(){
				$(this).remove();
				genMbildes();
			});
		});
	
		function sortOn(){
			$("#elfinder").sortable({
				update: function(event, ui){
					var arr = $(this).sortable("toArray", {attribute: "data-bildite"});
					//console.debug(arr);
					sakMbildes();
					sakLbildes();
				}
			});
				
			$("#elfinder").disableSelection();
		}
	
		$().ready(function(){
	
			
			$(".maza-bilde").each(function(e){
				var text = $(this).text();
				text = text.replace(/\s/gi,"");
				arrMa = text.split(",");
				//console.log(text);
				$(this).empty();
				$(this).append("<img src=\"'.e_PLUGIN_ABS.LA_KAT_DIR.'images/"+arrMa[0]+"\">");
			});
			
			
			var bildeM = $("input[id$=\"bilde-m\"]");
			var bildeL = $("input[id$=\"bilde-l\"]");
			
			bildeM.hide();
			bildeL.hide();
			
			//bildel = bildel.val();
			//bildem = bildem.val();
			
			genMbildes();
			atrLbildes();
			
			sortOn();
			
			// getter
			//var items = $( "#elfinder" ).sortable( "option", "items" );
			
			// setter
			//$( "#elfinder" ).sortable( "option", "items", "div.bildite" );
			
			$("input[id$=\"nosaukums\"]").on("keyup", function(){
				//$(this).css("border", "1px solid red");
				$("input[id$=\"alias\"]").val($(this).val().seoURL({"transliterate": true, "lowercase": true}));
				$(".alias-a").html($(this).val().seoURL({"transliterate": true, "lowercase": true}));
			})
		
		});
	
	');

	e107::js(LA_KAT, 'js/la-admin-js.js', 'jquery');

	class plugin_la_katalogs_admin extends e_admin_dispatcher{
		/**
		* Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
		* Note - default mode/action is autodetected in this order:
		* - $defaultMode/$defaultAction (owned by dispatcher - see below)
		* - $adminMenu (first key if admin menu array is not empty)
		* - $modes (first key == mode, corresponding 'index' key == action)
		* @var array
		*/
		protected $modes = array(
			'katalogs'		=> array('controller' => 'plugin_la_katalogs_adm_pre', 'path' => null, 'ui' => 'plugin_la_katalogs_adm_pre_form_ui', 'uipath' => null),
			'kategorijas'	=> array('controller' => 'plugin_la_katalogs_adm_kat', 'path' => null, 'ui' => 'plugin_la_katalogs_adm_kat_form_ui', 'uipath' => null),
			'razotaji'		=> array('controller' => 'plugin_la_katalogs_adm_raz', 'path' => null, 'ui' => 'plugin_la_katalogs_adm_raz_form_ui', 'uipath' => null),
			'diagnostika'	=> array('controller' => 'plugin_la_katalogs_adm_diag', 'path' => null, 'ui' => 'plugin_la_katalogs_adm_diag_form_ui', 'uipath' => null)
		);
		
		/* Both are optional */
		protected $defaultMode = "katalogs";
		protected $defaultAction = "list";
		
		
		/**
		* Format: 'MODE/ACTION' => array('caption' => 'Menu link title' [, 'url' => '{e_PLUGIN}blank/admin_config.php', 'perm' => '0']);
		* Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
		* @var array
		*/
		
		/** 
         * XXX the NEW version of e_admin_menu(); 
         * Build admin menus - addmin menus are now supporting unlimitted number of submenus
         * TODO - add this to a handler for use on front-end as well (tree, sitelinks.sc replacement)
         *
         * $e107_vars structure:
         * $e107_vars['action']['text'] -> link title
         * $e107_vars['action']['link'] -> if empty '#action' will be added as href attribute
         * $e107_vars['action']['image'] -> (new) image tag
         * $e107_vars['action']['perm'] -> permissions via getperms()
         * $e107_vars['action']['userclass'] -> user class permissions via check_class()
         * $e107_vars['action']['include'] -> additional <a> tag attributes
         * $e107_vars['action']['sub'] -> (new) array, exactly the same as $e107_vars' first level e.g. $e107_vars['action']['sub']['action2']['link']...
         * $e107_vars['action']['sort'] -> (new) used only if found in 'sub' array - passed as last parameter (recursive call)
         * $e107_vars['action']['link_class'] -> (new) additional link class
         * $e107_vars['action']['sub_class'] -> (new) additional class used only when sublinks are being parsed
         *
         * @param string $title
         * @param string $active_page
         * @param array $e107_vars
         * @param array $tmpl
         * @param array $sub_link
         * @param bool $sortlist
         * @return string parsed admin menu (or empty string if title is empty)
         */
		
		
		protected $adminMenu = array(
			'other'				=> array('divider'=> true),
			'other1'				=> array('header'=> "Precēm"),
			'katalogs/list'			=> array('text'=> LAN_PLUGIN_LA_KAT_SP_ADM_PR_SAR, 'perm' => '0', 'sub' => array( 'sub/first' => array('text'=> "1 sub link", 'perm' => '0'), 'sub/second' =>  array('text'=> "2 sub link", 'perm' => '0') ) ) ,
			
			
			'katalogs/create' 		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_PIEV_PR, 'perm' => '0', 'include' => "data-href='jkljksjgks'"),
			
			'other2'				=> array('divider'=> true),
			'other3'				=> array('header'=> "Kategorijām"),
			'kategorijas/list' 		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_KAT, 'perm' => '0'),
			'kategorijas/create' 	=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_PIEV_KAT, 'perm' => '0'),
			
			'other4'				=> array('divider'=> true),
			'other5'				=> array('header'=> "Ražotājiem"),
			'razotaji/list' 		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_RAZ, 'perm' => '0'),
			'razotaji/create' 		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_PIEV_RAZ, 'perm' => '0'),
			
			'other6'				=> array('divider'=> true),
			'other7'				=> array('header'=> "Katalogam"),
			'katalogs/prefs' 		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_IEST, 'perm' => '0'),
			'diagnostika/parbaude' 		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_DIAG, 'perm' => '0'),
			//'katalogs/test'		=> array('caption'=> LAN_PLUGIN_LA_KAT_SP_ADM_TESTA, 'perm' => '0')
		);
		
		/**
		* Optional, mode/action aliases, related with 'selected' menu CSS class
		* Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
		* This will mark active main/list menu item, when current page is main/edit
		* @var array
		*/
		protected $adminMenuAliases = array(
			'katalogs/edit'	=> 'katalogs/list'
		);
		
		/**
		* Navigation menu title
		* @var string
		*/
		protected $menuTitle = LAN_PLUGIN_LA_KAT_SP_MENU_NAME;
	}
	
	
	class plugin_la_katalogs_admin_form_ui extends e_admin_form_ui{
	
	function Sk(){
		require_once('controllers/la_katalogs_class.php');
		$plugin = new plugin_la_katalogs_index_controller();
		print_r( $plugin->skaits());
	}
	
	//Sk();
	
	
	
		// not really necessary since we can use 'dropdown' - but just an example of a custom function.
		function la_katatalogs_type($curVal,$mode){
			$frm = e107::getForm();
		
			$types = array('type_1'=>"Type 1", 'type_2' => 'Type 2');
		
			if($mode == 'read'){
				return vartrue($types[$curVal]).' (custom!)';
			}
			
			// Custom Batch List for blank_type
			if($mode == 'batch'){
				return $types;
			}
			
			// Custom Filter List for blank_type
			if($mode == 'filter'){
				return $types;
			}

			return $frm->select('type', $types, $curVal);
		}
	}


	/*
	* After initialization we'll be able to call dispatcher via e107::getAdminUI()
	* so this is the first we should do on admin page.
	* Global instance variable is not needed.
	* NOTE: class is auto-loaded - see class2.php __autoload()
	*/
	/* $dispatcher = */
	
	
	
	new plugin_la_katalogs_admin;
	
	
	/*
	* Uncomment the below only if you disable the auto observing above
	* Example: $dispatcher = new plugin_blank_admin(null, null, false);
	*/
	
	//$dispatcher = new plugin_la_katalogs_admin(null, null, false);
	
	//$dispatcher->runObservers(true);
	
	require_once(e_ADMIN."auth.php");
	
	/*
	* Send page content
	*/
	
	e107::getAdminUI()->runPage();
	
	//return e107::getAdminUI()->getHeader();
	
	
	
	
	
	//require_once(e_ADMIN."footer.php");
	
	/* OBSOLETE - see admin_shortcodes::sc_admin_menu()
	function admin_config_adminmenu() 
	{
		//global $rp;
		//$rp->show_options();
		e107::getRegistry('admin/blank_dispatcher')->renderMenu();
	}
	*/
	
	/* OBSOLETE - done within header.php
	function headerjs() // needed for the checkboxes - how can we remove the need to duplicate this code?
	{
		return e107::getAdminUI()->getHeader();
	}
	*/
?>