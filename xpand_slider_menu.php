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

require_once(e_PLUGIN."sliderV2/sliderV2.php");
//require_once(e_HANDLER.'shortcode_handler.php');
$text = $sliderV2;
$caption = "";

$ns -> tablerender($caption,$text);

//echo $text;

?>