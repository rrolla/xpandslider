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



if (!defined('e107_INIT')) { exit; }

$menutitle = "sliderV2";

$butname[] = "Bilder";
$butlink[] = "admin_config.php";
$butid[]   = "viewnews";

$butname[] = "uploader";
$butlink[] = "admin_upload.php";
$butid[]   = "readme";

//$butname[] = "sliderV2 menu Eintrag";
//$butlink[] = "admin_sliderV2.php";
//$butid[]   = "config";

//$butname[] = "neuer eintrag  neu !";
//$butlink[] = "admin_addqnews2.php";
//$butid[]   = "addnews";

//$butname[] = "neue Kathegorie";
//$butlink[] = "newcat.php";
//$butid[]   = "neuerEintrag";


//$butname[] = "admin_categories";
//$butlink[] = "admin_categories.php";
//$butid[]   = "readme2";
//
//$butname[] = "admin_upload";
//$butlink[] = "admin_upload.php";
//$butid[]   = "readme3";

//$butname[] = "admin_upload2";
//$butlink[] = "upload/index.php";
//$butid[]   = "config2";

$butname[] = "Readme";
$butlink[] = "admin_readme.php";
$butid[]   = "readme4";
global $pageid;
for ($i=0; $i<count($butname); $i++) {
	$var[$butid[$i]]['text'] = $butname[$i];
	$var[$butid[$i]]['link'] = $butlink[$i];
};

show_admin_menu($menutitle, $pageid, $var);
?>


