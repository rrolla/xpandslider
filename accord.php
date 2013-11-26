<?php


/**
*         e107 website system 
*         Plugin File :  e107_plugins/sliderV2/plugin.php
*        Email: 
*        $Revision  1.0$
*         $Date       12.2.2009$
*         $Author    Hups$
*         Support Sites :http://www.hupsis-e107.de/theme/news.php$ 
*/
 	require_once("bg_list.php");
	require_once("bilder_list.php");
	require_once("list_icon.php");
$accord .="
<script src='".e_PLUGIN."sliderV2/js/jquery.dimensions.js'></script>
<script src='".e_PLUGIN."sliderV2/js/jquery.accordion.js'></script>
<link href='".e_PLUGIN."sliderV2/css/accordion.css' rel='stylesheet' type='text/css' />
<script src='".e_PLUGIN."sliderV2/js/accord.js'></script>
";
	
	
$accord .= '	<div class="basic" id="list1b">
			
				<a >Navi Background</a>
			<div>
				
			'.$bgnav.'
				
				
				
			</div>
			
			
			<a >Bilder</a>
			<div>
				
			'.$listbild.'
				
				
				
			</div>
			<a>Icons</a>
			<div>'.$icon.'
				
			</div>
	  </div>';

?>