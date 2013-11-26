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


// class2.php is the heart of e107, always include it first to give access to e107 constants and variables
require_once("../../class2.php");

// Include auth.php rather than header.php ensures an admin user is logged in
require_once(e_ADMIN."auth.php");


 //Check to see if the current user has admin permissions for this plugin
if (!getperms("P")) {
	// No permissions set, redirect to site front page
	header("location:".e_BASE."index.php");
	exit;
}

// Get language file (assume that the English language file is always present)
$lan_file = e_PLUGIN."sliderV2/languages/".e_LANGUAGE.".php";
include_lan($lan_file);


$pageid = 'admin_menu_08';
// Set the active menu option for admin_menu.php






foreach($_POST['deleteconfirm'] as $key=>$delfile){
	// check for delete.
	if (isset($_POST['selectedfile'][$key]) && isset($_POST['deletefiles'])) {
		if (!$_POST['ac'] == md5(ADMINPWCHANGE)) {
			exit;
		}
		//$destination_file = e_BASE.$delfile;
		$destination_file = $delfile;
		if (@unlink($destination_file)) {
			$message .= sliderV2_UPLOAD_26." '".$destination_file."' ".sliderV2_UPLOAD_27.".<br />";
		} else {
			$message .= sliderV2_UPLOAD_28." '".$destination_file."'.<br />";
		}
	}

 
}

if (isset($_POST['upload'])) {
	if (!$_POST['ac'] == md5(ADMINPWCHANGE)) {
		exit;
	}
	$pref['upload_storagetype'] = "1";
	require_once(e_HANDLER."upload_handler.php");
	$files = $_FILES['file_userfile'];
	foreach($files['name'] as $key => $name) {
		if ($files['size'][$key]) {
			//$uploaded = file_upload(e_BASE.$_POST['upload_dir'][$key]);
			$uploaded = file_upload($_POST['upload_dir'][$key]);
		}
	}
}

if (isset($message)) {
	$ns->tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

//if (strpos(e_QUERY, ".") && !is_dir(realpath(e_BASE.$path))){
//	echo "";
// sliderV2 adjustment for displaying the image: exclude the ../ from showing!
if (e_QUERY != "" && substr(e_QUERY,-3) != "../" ) {
	echo "";
	if (!strpos(e_QUERY, "/")) {
		$path = "";
	} else {
		$path = substr($path, 0, strrpos(substr($path, 0, -1), "/"))."/";
	}
}

// Retrieve shop preferences to get image path
$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
while($row = $sql-> db_Fetch()){
  $store_image_path = $row['store_image_path'];
}
$store_image_path="images/";
$path = e_PLUGIN."sliderV2/images/";

$files = array();
$dirs = array();
$path = explode("?", $path);
$path = $path[0];
$path = explode(".. ", $path);
$path = $path[0];

//if ($handle = opendir(e_BASE.$path)) {
if ($handle = opendir($path)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {

			//if (getenv('windir') && is_file(e_BASE.$path."\\".$file)) {
			if (getenv('windir') && is_file($path."\\".$file)) {
				//if (is_file(e_BASE.$path."\\".$file)) {
				if (is_file($path."\\".$file)) {
					$files[] = $file;
				} else {
					$dirs[] = $file;
				}
			} else {
				//if (is_file(e_BASE.$path."/".$file)) {
				if (is_file($path."/".$file)) {
					$files[] = $file;
				} else {
					$dirs[] = $file;
				}
			}
		}
	}
}
// sliderV2 modification; add an upload 'directory'; so we can upload from showing the list of images

			

$dirs[] = "..";
closedir($handle);

if (count($files) != 0) {
	arsort($files);
}
if (count($dirs) != 0) {
	arsort($dirs);
}

if (count($files) == 1) {
	$cstr = sliderV2_UPLOAD_12;
} else {
	$cstr = sliderV2_UPLOAD_13;
}

if (count($dirs) == 1) {
	$dstr = sliderV2_UPLOAD_14;
} else {
	$dstr = sliderV2_UPLOAD_15;
}

$pathd = $path;





$upl = "
<!-- <script src='".e_PLUGIN."sliderV2/js/jquery.min.js'></script> -->
<script src='".e_PLUGIN."sliderV2/js/thickbox.js'></script>

<link href='".e_PLUGIN."sliderV2/css/thickbox.css' rel='stylesheet' type='text/css' />
<!-- <link href='".e_PLUGIN."sliderV2/css/style.css' rel='stylesheet' type='text/css' /> -->
<style type=\"text/css\">
#hiddenelement0
{
  display:none;
}
#hiddenelement1
{
  display:none;
}
#hiddenelement3
{
  display:none;
}
#hiddenelement4
{
  display:none;
}
 </style>
 <script language=\"javascript\"> 
function toggle(element)
{
  doc = document.getElementById(element);
  if(doc.style.display == 'block')
    doc.style.display = 'none';
  else
    doc.style.display = 'block';

}
</script>

ROOT-Verzeichniss: <b>root/".$pathd."</b>&nbsp;&nbsp;[ ".count($dirs)." ".$dstr.", ".count($files)." ".$cstr." ]
<form enctype=\"multipart/form-data\" action=\"".e_SELF.(e_QUERY ? "?".e_QUERY : "")."\" method=\"post\">
	<div style=\"text-align:center\">
	<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\" />
	<table class='fborder' style=\"100%\">";

$upl .= "<tr>
	<td style=\"width:5%\" class=\"fcaption\">Pfad</td>
	<td style=\"width:30%\" class=\"fcaption\"><b>".sliderV2_UPLOAD_17."</b></td>
	<td class=\"fcaption\"><b>".sliderV2_UPLOAD_18."</b></td>
	<td style=\"width:30%\" class=\"fcaption\"><b>".sliderV2_UPLOAD_19."</b></td>
	<td class=\"fcaption\"><b>Upload</b></td>
	</tr>
	
	";

if ($path != e_FILE) {
	if (substr_count($path, "/") == 1) {
		$pathup = e_SELF;
	} else {
		$pathup = e_SELF."?".substr($path, 0, strrpos(substr($path, 0, -1), "/"))."/";
	}
 
}

$c = 0;
while ($dirs[$c]) {
	$dirsize = dirsize($path.$dirs[$c]);
	$upl .= "<tr>
		<td class=\"forumheader3\" style=\"vertical-align:middle; text-align:center; width:5%\">
		".$path.$dirs[$c]."
		</td>
		<td style=\"width:30%\" class=\"forumheader3\">
		<input id=\"imageDivLink\" type=\"button\" value=\"".$dirs[$c]."\" onclick=\"javascript:toggle('hiddenelement".$c."');\">
			
			
		</td>
		<td class=\"forumheader3\">".$dirsize."
		</td>
		<td class=\"forumheader3\">&nbsp;</td>
		<td class=\"forumheader3\">";
	//if (FILE_UPLOADS && is_writable(e_BASE.$path.$dirs[$c])) {
	if (FILE_UPLOADS && is_writable($path.$dirs[$c])) {
    if (substr($path.$dirs[$c],-3) == "/..") {
      // For the root path we strip the last three characters (/..)
      $dirname = substr($path.$dirs[$c],0,-3);
    } else { // other directories show the correct path already
      $dirname = $path.$dirs[$c];
    }
		$upl .= "<input type=\"button\" name=\"erquest\" value=\"".sliderV2_UPLOAD_21."\" onclick=\"javascript:toggle('hiddenelement".$c."');\">

			<div id='hiddenelement".$c."' >
			
			<div id='up_container' >
			<span id='upline' style='white-space:nowrap'>
			<input class=\"tbox\" type=\"file\" name=\"file_userfile[]\" size=\"50\" />
			<input  type=\"submit\" name=\"upload\" value=\"".sliderV2_UPLOAD_22."\" />
			<input type=\"hidden\" name=\"upload_dir[]\" value=\"".$dirname."\" />
			</span><br /></div>
			
			";
	} else {
		$upl .= "&nbsp;";
	}
	$upl .= "	</td>
		</tr>
		";
	
	$c++;
	

		

}
/**
* auslesen verzeichniss Anfang
*/
require_once("accord.php");
	$text .= "".$accord."";
/**
* ausgabe ENDE!
*/		
//$c = 0;
//while ($files[$c]) {
//	$img = substr(strrchr($files[$c], "."), 1, 3);
//	if (!$img || !preg_match("/css|exe|gif|htm|jpg|js|php|png|txt|xml|zip/i", $img)) {
//		$img = "def";
//	}
//	//$size = parsesize(filesize(e_BASE.$path."/".$files[$c]));
//	$size = parsesize(filesize($path."/".$files[$c]));
//	
//
//	$upl .= "</table>
//
//		
//
//		";
//
//

//	$upl .= "<table>	
//	
//	
//	
//	
//	<tr>
//		<td class=\"forumheader3\" style=\"vertical-align:middle; text-align:center; width:5%\">
//		<a rel=''class='thickbox'href=\"".$path.$files[$c]."\"><img style='width:50px'src=\"".$pathd."".$files[$c]."\" alt=\"".$files[$c]."\" style=\"border:0\" />
//		</td>
//		<td style=\"width:30%\" class=\"forumheader3\">
//		<a rel=''class='thickbox'href=\"".$path.$files[$c]."\">".$files[$c]."</a>
//		</td>";
//	$gen = new convert;
//	//$filedate = $gen -> convert_date(filemtime(e_BASE.$path."/".$files[$c]), "forum");
//	$filedate = $gen -> convert_date(filemtime($path."/".$files[$c]), "forum");
//	$upl .= "<td style=\"width:10%\" class=\"forumheader3\">".$size."</td>
//		<td style=\"width:30%\" class=\"forumheader3\">".$filedate."</td>
//		<td class=\"forumheader3\">";
//
//	$upl .= "<input  type=\"checkbox\" name=\"selectedfile[$c]\" value=\"1\" />";
//	$upl .="<input type=\"hidden\" name=\"deleteconfirm[$c]\" value=\"".$path.$files[$c]."\" />";
//
//	$upl .="</td>
//		</tr>";
//	$c++;
//}

	$upl .= "<tr>
	
	
	<td colspan='5' class='forumheader' style='text-align:right'>";


	$upl .= "
		</td></tr></table></div>
		<input type='hidden' name='ac' value='".md5(ADMINPWCHANGE)."' />
	
		</form>";
	/**
* icons auslesen verzeichniss Anfang
*/
require_once("list_icon.php");
/**
* ausgabe ENDE!
*/
$ns->tablerender($upl, $text);

function dirsize($dir) {
	$_SERVER["DOCUMENT_ROOT"].e_HTTP.$dir;
	$dh = @opendir($_SERVER["DOCUMENT_ROOT"].e_HTTP.$dir);
	$size = 0;
	while ($file = @readdir($dh)) {
		if ($file != "." and $file != "..") {
			$path = $dir."/".$file;
			if (is_file($_SERVER["DOCUMENT_ROOT"].e_HTTP.$path)) {
				$size += filesize($_SERVER["DOCUMENT_ROOT"].e_HTTP.$path);
			} else {
				$size += dirsize($path."/");
			}
		}
	}
	@closedir($dh);
	return parsesize($size);
}

function parsesize($size) {
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;
	if ($size < $kb) {
		return $size." b";
	}
	else if($size < $mb) {
		return round($size/$kb, 2)." kb";
	}
	else if($size < $gb) {
		return round($size/$mb, 2)." mb";
	}
	else if($size < $tb) {
		return round($size/$gb, 2)." gb";
	} else {
		return round($size/$tb, 2)." tb";
	}
}

require_once(e_ADMIN."footer.php");
?>