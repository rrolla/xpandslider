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

	
	$sql = new db;
	$res = $sql->retrieve('sliderv2', '*', 'visible=\'1\' ORDER BY id DESC', true, null, false);
		
	//$res = mysql_query($sql);
	//$count = mysql_num_rows($res);
	//$row = mysql_fetch_object($res);
	
	//print_r($res);
	
	/*
	$id = $row->id;
	$titel = $row->sliderv2_titel;
	$url = $row->sliderv2_url;
	$text = $row->sliderv2_text;
	$height = $row->height;
	$width= $row->width;
	$script = $row->jquery;
	$port = $row->port;
	*/
	
	$port = $res[0][port];
 
	/*
	if($script =="ok.png"){
		$sliderV2  .= '<!-- <script src="'.e_PLUGIN.'sliderV2/js/jquery-1.2.6.js" type="text/javascript"></script> -->';
	}else{
		$sliderV2  .= '';
	}
	*/
	
	
	$sliderV2 .= '
	<link href="'.e_PLUGIN.'sliderV2/js/coda-slider.css" rel="stylesheet" type="text/css" media="screen" title="no title" charset="utf-8">
	<script src="'.e_PLUGIN.'sliderV2/js/jquery.scrollTo-1.3.3.js" type="text/javascript"></script>
	<script src="'.e_PLUGIN.'sliderV2/js/jquery.localscroll-1.2.5.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.e_PLUGIN.'sliderV2/js/jquery.serialScroll-1.2.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.e_PLUGIN.'sliderV2/js/coda-slider.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.e_PLUGIN.'sliderV2/js/JSFX_ImageFader.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.e_PLUGIN.'sliderV2/js/thickbox.js" type="text/javascript" ></script>
	<link href="'.e_PLUGIN.'sliderV2/css/thickbox.css" rel="stylesheet" type="text/css" />
	';
	
	//$sql2 = "SELECT * FROM e107_sliderv2 ORDER BY id DESC";
	//$res = mysql_query($sql2);
	
	//print_r($res);
	
	//$row = mysql_fetch_object($res);
	/*
	$height = $row->height;
	$width= $row->width;
	$id = $row->sliderv2_id;
	$titel = $row->sliderv2_titel;
	$text = $row->sliderv2_text;
	$port = $row->port;
	*/
	
	$sliderV2 .= '
	<!-- </style> -->
	<div id="wrapper">
		<div id="slider">    
			<div id="portfolio_menu" style="overflow: hidden; background-image: url('.e_PLUGIN.'sliderV2/'.$port.')">
			<ul class="navigation">
	';
	//$sql1 = new db;			
	//$sql1 = "SELECT `e107_sliderv2`.`visible`, `e107_sliderv2`.`sliderv2_url` FROM `e107_sliderv2` WHERE `e107_sliderv2`.`visible` = 1 ORDER BY id DESC";
	
	//$res = mysql_query($sql2);
	
	foreach($res as $itm){
		$sliderV2 .= '</li><a href="#'.$itm[id].'" id="menuitem-'.$itm[id].'" />
		<img width="'.$itm[width].'" height="'.$itm[$height].'" src="'.e_PLUGIN.'sliderV2/'.$itm[sliderv2_url].'" border="0" onMouseOver="JSFX.fadeUp(this)" onMouseOut="JSFX.fadeDown(this)" class="imageFader"/></a></li>';
	}
	
	$sliderV2 .= '
			</ul>
		</div>
	';
	
	/*
	$sliderV2 .= '
		<img class="scrollButtons left" src="'.e_PLUGIN.'sliderV2/js/portfolio_navlefthover.gif"border="0"onMouseOver="JSFX.fadeUp(this)" onMouseOut="JSFX.fadeDown(this)" class="imageFader"/>
		<img class="scrollButtons right2x" src="'.e_PLUGIN.'sliderV2/js/portfolio_navrighthover.gif" border="0"onMouseOver="JSFX.fadeUp(this)" onMouseOut="JSFX.fadeDown(this)" class="imageFader"/>
	';
	*/

	$sliderV2 .= '<div  class="forumheader2">       
		  <div style="overflow: hidden;" class="scroll" >
		  
                <div  class="scrollContainer">
                ';

		



	
	// innere Schleife - Abfrage der einzelnen sliders
	$sql2 = "SELECT * FROM ".MPREFIX."sliderv2 WHERE  visible='1' ORDER BY id DESC";
		
	$res = mysql_query($sql2);
	
	while($row = mysql_fetch_object($res)){
	
		$sliderV2 .= '
<div style="float: left; position: relative;" class="panel" id="'.$row->id.'">

<div class="titelslider">'.$row->sliderv2_titel.'</div><br />
'.$row->sliderv2_text.'</div>	
';
}

	
	
	$sliderV2 .= ' 
	</div>
		<div id="shade"></div>            
			</div>        
				</div>
				 	</div>'; 
?>