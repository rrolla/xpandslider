<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom install/uninstall/update routines for blank plugin
**
*/

	define("DB_SLIDER", "xpand_slider"); // datubazes nosaukums

class xpandSlider_setup{

 	function install_pre($var){
		//print_a($var);
		// echo "custom install 'pre' function<br /><br />";
	}

	/**
	 * For inserting default database content during install after table has been created by the blank_sql.php file. 
	 */
	function install_post($var){
		$sql = e107::getDb();
		$mes = e107::getMessage();
		
		$xpndSld_opts = array('transition' => 'random', 'extra' => 'realy', 'dynamic' => 'high');
		$xpndSld_opts = serialize($xpndSld_opts);
		
		$xpandSlider = array(
			'xpand_slider_title'		=> 'Xpand Slider Title!',
			'xpand_slider_content'	=> '<b>Xpand Slider HTML content!</b>',
			'xpand_slider_url'	=> '',
			'xpand_slider_imgs'	=> '',
			'xpand_slider_opts'	=> $xpndSld_opts,
			'xpand_slider_class'	=> 0,
			'xpand_slider_order'	=> 0,
		);
		

		if($sql->insert(DB_SLIDER, $xpandSlider)){
			$mes->add("Xpand Slider installed!", E_MESSAGE_SUCCESS);
		}else{
			$mes->add("Error installing Xpand Slider!", E_MESSAGE_ERROR);	
		}
		
	}
	
	function uninstall_options(){
	
		$listoptions = array(0 => 'option 1', 1 => 'option 2');
		
		$options = array();
		$options['mypref'] = array(
				'label'		=> 'Custom Uninstall Label',
				'preview'	=> 'Preview Area',
				'helpText'	=> 'Custom Help Text',
				'itemList'	=> $listoptions,
				'itemDefault'	=> 1
		);
		
		return $options;
	}
	

	function uninstall_post($var){
		//print_a($var);
	}

	function upgrade_post($var){
		// $sql = e107::getDb();
	}
	
}
?>