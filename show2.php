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
require_once("../../class2.php");
// Check current user is an admin, redirect to main site if not
if (!getperms("P")) {
	header("Location: ".e_HTTP."index.php");
	exit;
}
include_once(e_PLUGIN . "sliderV2/languages/" . e_LANGUAGE . ".php");
/* GET VARS */
$show = ( isset($_GET['show'])  && intval($_GET['show'])  !== 0 ) ? TRUE : FALSE;
$text   .= "
<script src='".e_PLUGIN."sliderV2/js/jquery.min.js'></script>
<script src='".e_PLUGIN."sliderV2/js/thickbox.js'></script>
<link href='".e_PLUGIN."sliderV2/css/thickbox.css' rel='stylesheet' type='text/css' />
<link href='".e_PLUGIN."sliderV2/css/style.css' rel='stylesheet' type='text/css' />
";
/* Render */
$mysql = new db();
$mysql->db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);
if (  $show === FALSE) {
echo "	<div class='normaltext'align='center'>nix</div>
		
		";	
} elseif ( $show === TRUE ) {
		$id = intval(ereg_replace("[^0-9]", "", $_GET['show']));
		$row_count = $mysql->db_Select("sliderV2", "*", " id = $id LIMIT 1", TRUE);
		if ( intval($row_count) > 0 ) {
			/* Show mod form */
			$row = $mysql->db_Fetch();
	echo "	<div class='normaltext'align='center'>".$row['sliderv2_text']."	</div>
		
		";
		}
}
$mysql->db_Close();
echo $text;
?>