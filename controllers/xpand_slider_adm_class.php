<?php

	class plugin_la_katalogs_adm_pre extends e_admin_ui{
		// required
		protected $pluginTitle = LAN_PLUGIN_LA_KAT_SP_NAME;

		/**
		 * plugin name or 'core'
		 * IMPORTANT: should be 'core' for non-plugin areas because this
		 * value defines what CONFIG will be used. However, I think this should be changed
		 * very soon (awaiting discussion with Cam)
		 * Maybe we need something like $prefs['core'], $prefs['blank'] ... multiple getConfig support?
		 *
		 * @var string
		 */
		protected $pluginName = LA_KAT;

		/**
		 * DB Table, table alias is supported
		 * Example: 'r.blank'
		 * @var string
		 */
		protected $table = DB_KATALOGS;

		/**
		 * If present this array will be used to build your list query
		 * You can link fileds from $field array with 'table' parameter, which should equal to a key (table) from this array
		 * 'leftField', 'rightField' and 'fields' attributes here are required, the rest is optional
		 * Table alias is supported
		 * Note:
		 * - 'leftTable' could contain only table alias
		 * - 'leftField' and 'rightField' shouldn't contain table aliases, they will be auto-added
		 * - 'whereJoin' and 'where' should contain table aliases e.g. 'whereJoin' => 'AND u.user_ban=0'
		 *
		 * @var array [optional] table_name => array join parameters
		 */
		protected $tableJoin = array(
			//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
		);

		/**
		 * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
		 * Write your list query without any Order or Limit.
		 *
		 * @var string [optional]
		 */
		protected $listQry = "";
		//

		// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
		//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

		// required - if no custom model is set in init() (primary id)
		protected $pid = "la_kat_pre_id";

		// optional
		protected $perPage = 50;

		// default - true - TODO - move to displaySettings
		protected $batchDelete = true;
		
		protected $batchCopy = true;
		
		protected $listOrder = 'la_kat_pre_order, la_kat_pre_id DESC';
		protected $sortField = 'la_kat_pre_order';

		// UNDER CONSTRUCTION
		protected $displaySettings = array();

		// UNDER CONSTRUCTION
		protected $disallowPages = array('katalogs/create', 'katalogs/prefs');

		//TODO change the blank_url type back to URL before blank.
		// required
		/**
		 * (use this as starting point for wiki documentation)
		 * $fields format (string) $field_name => (array) $attributes
		 *
		 * $field_name format:
		 * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
		 * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
		 * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
		 *
		 * $attributes format:
		 * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
		 *
		 *  - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
		 *    boolean, method, ip
		 *  	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
		 *  	for list of possible read/writeParms per type see below
		 *
		 *  - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
		 *    Default is 'str'
		 *    Used only if $dataFields is not set
		 *  	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
		 *  - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
		 *  - primary (boolean) primary field (obsolete, $pid is now used)
		 *
		 *  - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
		 *  - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
		 *
		 *  - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
		 *  - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
		 *  - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
		 *
		 *  - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
		 *    NOTE: batch may accept string values in the future...
		 *  	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
		 *
		 *  - filter (boolean) list table - add current field to filter actions, rest is same as batch
		 *
		 *  - forced (boolean) list table - forced fields are always shown in list table
		 *  - nolist (boolean) list table - don't show in column choice list
		 *  - noedit (boolean) edit table - don't show in edit mode
		 *
		 *  - width (string) list table - width e.g '10%', 'auto'
		 *  - thclass (string) list table header - th element class
		 *  - class (string) list table body - td element additional class
		 *
		 *  - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
		 *    depends on the current field type (see below). readParams are used mainly by list page
		 *
		 *  - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
		 *    Structure on this attribute depends on the current field type (see below).
		 *    writeParams are used mainly by edit page, filter (list page), batch (list page)
		 *
		 * $attributes['type']->$attributes['read/writeParams'] pairs:
		 *
		 * - null -> read: n/a
		 * 		  -> write: n/a
		 *
		 * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
		 * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
		 *
		 * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
		 * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
		 * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
		 *
		 * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
		 * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
		 *
		 * - ip		-> read: n/a
		 * 			-> write: [optional] element options array (see e_form class description for __options format)
		 *
		 * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
		 * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
		 *
		 
		 *
		 * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing),
		 *								[optional] 'bb' => '1' (parse bbcode) default 0,
		 * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', 
		 *								[optional] 'htmltruncate' => 100,
		 * 								[optional] 'truncate' => 100, 
		 *								[optional] 'expand' => '[more]' title for expand link, empty - no expand
		 *								
		 * 		  		-> write: (array) [optional] 'rows' => '' default 15, 
		 *								[optional] 'cols' => '' default 40, 
		 *								[optional] '__options' => array(...) see e_form class description for __options format
		 * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
		 *
		 
		 * - bbarea -> read: same as textarea type
		 * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
		 * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
		 * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
		 *
		 * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
		 * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
		 * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
		 * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
		 *
		 * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
		 * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
		 *
		 * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
		 * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
		 *
		 * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
		 * 			-> write:
		 *
		 * - method -> read: optional, passed to given method (the field name)
		 * 			-> write: optional, passed to given method (the field name)
		 *
		 * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
		 * 			-> write: same as readParms
		 *
		 * - upload -> read: n/a
		 * 			-> write: Under construction
		 *
		 * Special attribute types:
		 * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
		 * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
		 * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
		 * 		Return type expected (by render action):
		 * 			- read: list table - formatted value only
		 * 			- write: edit table - form element (control)
		 * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
		 * 			- filter: same as batch
		 * @var array
		 */
		 
		 //echo '<div id="elfinder"></div>';
		 
		protected  $fields = array(
		'checkboxes' => array('title' => '', 'type' => null, 'data' => null, 'width' =>'5%', 'thclass' => 'center', 'forced' => TRUE, 'class' => 'center', 'toggle' => 'e-multiselect'),
			'la_kat_pre_id' => array('title' => LAN_ID, 'type' => 'number', 'data' => 'int', 'width' =>'2%', 'thclass' => '', 'class' =>'', 'forced' => TRUE, 'primary' => TRUE),
			// 'noedit' => TRUE), Primary ID is not editable
			
           	'la_kat_pre_kods' => array('title' => LAN_PLUGIN_LA_KAT_PR_KODS, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' => '', 'help' => LAN_PLUGIN_LA_KAT_PR_HELP_KODS, 'inline' => TRUE, 'forced' => TRUE, 'batch' => TRUE, 'validate' => TRUE),
			
            'la_kat_pre_razotajs' => array('title' => LAN_PLUGIN_LA_KAT_PR_RAZOTAJS, 'type' => 'dropdown', 'data'=> 'int', 'width' => '10%', 'thclass' => '', 'class' => '', 'help' => LAN_PLUGIN_LA_KAT_PR_HELP_RAZOTAJS, 'inline' => TRUE, 'filter'=> TRUE, 'batch' => TRUE),
			
			'la_kat_pre_kategorija' => array('title' => LAN_PLUGIN_LA_KAT_PR_KATEGORIJA, 'type' => 'method', 'width' => '10%', 'thclass' => '', 'class' => '', 'help' => LAN_PLUGIN_LA_KAT_PR_HELP_KATEGORIJA, 'inline' => TRUE, 'filter'=> TRUE, 'batch' => TRUE),
			
			'la_kat_pre_nosaukums' => array('title' => LAN_PLUGIN_LA_KAT_PR_NOSAUKUMS, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' => '', 'help' => LAN_PLUGIN_LA_KAT_PR_HELP_NOSAUKUMS, 'inline' => TRUE, 'forced' => TRUE, 'filter' => TRUE, 'batch' => TRUE, 'validate' => TRUE),
			
			'la_kat_pre_rup_nr' => array('title' => LAN_PLUGIN_LA_KAT_PR_RUPNICAS_NUMURS, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' => '', 'help' => LAN_PLUGIN_LA_KAT_PR_HELP_RUPNICAS_NUMURS, 'inline' => TRUE, 'forced' => TRUE, 'filter' => TRUE, 'batch' => TRUE, 'validate' => TRUE),
			
			'la_kat_pre_bilde_m' => array('title' => LAN_PLUGIN_LA_KAT_PR_BILDE_MAZ, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' =>'maza-bilde', 'forced' => TRUE),
			
			'la_kat_pre_bilde_l' => array('title' => LAN_PLUGIN_LA_KAT_PR_BILDE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' =>'bilzuizvele', 'nolist' => TRUE),
			
			'la_kat_pre_apraksts' => array('title' => LAN_PLUGIN_LA_KAT_PR_APRAKSTS, 'type' => 'bbarea', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' => '', 'readParms' => array('noparse' => 0, 'bb' => 1, 'expand' => 'atvērt editoru')),
			
			'la_kat_pre_noliktava' => array('title' => LAN_PLUGIN_LA_KAT_PR_NOLIKTAVA, 'type' => 'dropdown', 'data' => 'boolean', 'width' => '5%', 'thclass' => '', 'class' => '', 'readParms' => array(1 => 'Jā', 0 =>'Nē'), 'writeParms' => array(1 => 'Jā', 0 =>'Nē'), 'inline' => TRUE, 'forced' => TRUE, 'filter' => TRUE, 'batch' => TRUE),
			
         	'la_kat_pre_daudzums' => array('title' => LAN_PLUGIN_LA_KAT_PR_DAUDZUMS, 'type' => 'text', 'data' => 'int', 'width' => '5%', 'thclass' => '', 'class' => '', 'inline' => TRUE, 'filter' => TRUE, 'batch' => TRUE),
			
			
			'la_kat_pre_alias' => array('title' => LAN_PLUGIN_LA_KAT_PR_ALIAS, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' => '', 'note' => LAN_PLUGIN_LA_KAT_PR_HELP_ALIAS,),
			
			'la_kat_pre_datums' => array('title' => LAN_PLUGIN_LA_KAT_PR_DATUMS, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'class' => 'disabled', 'noedit' => TRUE, 'filter' => TRUE),
			
			'la_kat_pre_klase' => array('title'=> LAN_VISIBILITY, 'type' => 'userclass', 'data' => 'int',  'width' => 'auto', 'inline' => TRUE, 'filter' => TRUE, 'batch' => TRUE),
			
			'la_kat_pre_order' => array('title'=> LAN_PLUGIN_LA_KAT_PR_ORDER, 'type' => 'number', 'width' => 'auto', 'inline' => TRUE, 'filter' => TRUE, 'batch' => TRUE),
			
			/*			
			'url' => array('title' => LAN_URL, 'type' => 'file', 'data' => 'str', 'width' => '20%', 'thclass' => 'center', 'batch' => TRUE, 'filter'=>TRUE, 'parms' => 'truncate=30', 'validate' => false, 'help' => 'Enter blank URL here', 'error' => 'please, ener valid URL'),
			*/
			'options' => array('title'=> LAN_PLUGIN_LA_KAT_SP_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
		);

		//required - default column user prefs
		protected $fieldpref = array('checkboxes', 'la_kat_pre_id', 'la_kat_pre_kods', 'la_kat_pre_nosaukums', 'la_kat_pre_rup_nr', 'la_kat_pre_bilde_m', 'la_kat_pre_noliktava', 'la_kat_pre_daudzums');

		// FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
		/*protected $dataFields = array();*/

		// optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
		/*protected  $validationRules = array(
			'blank_url' => array('required', '', 'blank URL', 'Help text', 'not valid error message')
		);*/

		// optional, if $pluginName == 'core', core prefs will be used, else e107::getPluginConfig($pluginName);
		protected $prefs = array(
			'la_kat_iest_lapa'	   				=> array('title'=> LAN_PLUGIN_LA_KAT_SP_LAPA, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP1),
			'la_kat_iest_pre_w'	   				=> array('title'=> LAN_PLUGIN_LA_KAT_SP_PRE_W, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP2),
			'la_kat_iest_pre_h'	   				=> array('title'=> LAN_PLUGIN_LA_KAT_SP_PRE_H, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP3),
			'la_kat_iest_kat_w'	   				=> array('title'=> LAN_PLUGIN_LA_KAT_SP_KAT_W, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP4),
			'la_kat_iest_kat_h'	   				=> array('title'=> LAN_PLUGIN_LA_KAT_SP_KAT_H, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP5),
			'la_kat_iest_kat_enclose' 			=> array('title'=> LAN_PLUGIN_LA_KAT_SP_KAT_ENCLOSE, 'type' => 'bool', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP6),
			'la_kat_iest_breadc_sep'			=> array('title'=> LAN_PLUGIN_LA_KAT_SP_BREADC_SEP, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP7),
			'la_kat_iest_load_col'	 			=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_COL, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP1),
			'la_kat_iest_load_shape'	 		=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_SHAPE, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP2),
			'la_kat_iest_load_diam'	 			=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_DIAM, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP3),
			'la_kat_iest_load_density'	 		=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_DENSITY, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP4),
			'la_kat_iest_load_range'	 		=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_RANGE, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP5),
			'la_kat_iest_load_fps'	 			=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_FPS, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP6),
			'la_kat_iest_load_speed'	 		=> array('title'=> LAN_PLUGIN_LA_KAT_SP_CLOAD_SPEED, 'type'=>'number', 'data' => 'string', 'note' => LAN_PLUGIN_LA_KAT_SP_CLOAD_HELP7),
			'la_kat_iest_debug' 				=> array('title'=> LAN_PLUGIN_LA_KAT_SP_DEBUG, 'type' => 'bool', 'note' => LAN_PLUGIN_LA_KAT_SP_HELP8),
			//'la_kat_iest_3' 				=> array('title'=> 'la_kat_iest_3', 'type' => 'text', 'data' => 'string', 'validate' => 'regex', 'rule' => '#^[\w]+$#i', 'help' => 'allowed characters are a-zA-Z and underscore')
		);

		// optional
		public function init(){
			$sql = e107::getDB(); 	// mysql class object
			$sql->db_Set_Charset("utf8");
			
			$razotaji = array();
			if($sql->db_Select(DB_RAZOTAJI)){
				//$razotaji[0] = LAN_SELECT;
				while ($row = $sql->db_Fetch())
				{
					$id = $row['la_kat_raz_id'];
					//$tmpl = $row['fb_category_template'];
					$razotaji[$id] = $row['la_kat_raz_nosaukums'];
					//$menuCat[$tmpl] = $row['fb_category_title'];
				}
			}
	
			$this->fields['la_kat_pre_razotajs']['writeParms'] 		= $razotaji;	
			$this->fields['la_kat_pre_razotajs']['readParms'] 		= $razotaji;
			
			
			
			$kategorijas = array();
			if($sql->db_Select(DB_KATEGORIJAS)){
				//$kategorijas[0] = LAN_SELECT;
				while ($row = $sql->db_Fetch())
				{
					$id = $row['la_kat_kat_id'];
					//$tmpl = $row['fb_category_template'];
					$kategorijas[$id] = $row['la_kat_kat_nosaukums'];
					//$menuCat[$tmpl] = $row['fb_category_title'];
				}
			}
			
			
	
			//$this->fields['la_kat_pre_kategorija']['writeParms'] 		= $kategorijas;	
			//$this->fields['la_kat_pre_kategorija']['readParms'] 		= $kategorijas;
			
			//unset($menuCat['unassigned']);
			
			//$this->prefs['menu_category']['writeParms'] 	= $menuCat;
			//$this->prefs['menu_category']['readParms'] 		= $menuCat;
		}
		
		
		public function testPage(){
			$ns = e107::getRender();
			$text = "Hello World!";
			$ns->tablerender("Hello",$text);
		}
	}

	class plugin_la_katalogs_adm_pre_form_ui extends e_admin_form_ui{

		public function la_kat_pre_kategorija($curVal, $mode){
			require_once('la_katalogs_kategorijas_class.php');
			$kategorija = new la_katalogs_kategorijas;
			//print_r($kategorija->getCategorySelectList());
	
			// TODO - catlist combo without current cat ID in write mode, parents only for batch/filter 
			// Get UI instance
			//$controller = $this->getController();
			
			switch($mode){
			case 'read':
				//return e107::getParser()->toHTML($kategorija->getCategorySelectList($curVal), false, 'TITLE');
			break;
			
			case 'write':
			
			/**
			*
			* @param string $name
			* @param array $option_array
			* @param boolean $selected [optional]
			* @param string|array $options [optional]
			* @param boolean|string $defaultBlank [optional] set to TRUE if the first entry should be blank, or to a string to use it for the blank description. 
			* @return string HTML text for display
			*/
			//function select($name, $option_array, $selected = false, $options = array(), $defaultBlank= false)
			
				//return $this->select('la_kat_pre_kategorija', '<optgroup><option>...</option></optgroup><optgroup><option>333</option></optgroup>', 1, 'useValues', true );
				
				//echo $curVal;
				
				return $this->select('la_kat_pre_kategorija', $kategorija->getCategorySelectList($curVal), -1, null, true);
				
				//return e107::getParser()->toHTML($kategorija->getCategorySelectList($curVal), false, 'TITLE');
				
				//return $this->select('la_kat_pre_kategorija', array('test this' => array(1 => 'yoo'), 'party' => array(0 => 'test', 1 => 'testing', 'go' => array(0 => 'test', 1 => 'testing'))), $curVal, '', true);
				
			break;
			
			case 'filter':
			case 'batch':
				//return $kategorija->getCategorySelectList();
				//return array('singleOption' => '<option value="somethig">Title</option>');
			break;
			}
		}
	}

