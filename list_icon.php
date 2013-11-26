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

//---------------------------------------------------------------------------------	
/**
* auslesen verzeichniss Anfang
*/
$serverroot=$_SERVER['HTTP_HOST']; 
$pfad = "http://".$serverroot."".e_HTTP."e107_plugins/sliderV2/";	
$bilderliste = array();
$verzeichnis = "images/icons/";
$handle = openDir($verzeichnis);
if (isset($_POST['action'])) {
   if ($_POST['action'] == "del") {
      unlink("images/icons/" . $_POST['loeschen']) ;
   }
  }
while ($datei = readDir($handle)) {
 $verzeichnis_datei = $verzeichnis . $datei;
 if ($datei != "." && $datei != ".." && !is_dir($datei)) {
  if (strstr($datei, ".gif") || strstr($datei, ".png")|| strstr($datei, ".php")|| strstr($datei, ".rar")|| strstr($datei, ".zip")|| strstr($datei, ".pdf")|| strstr($datei, ".doc") || strstr($datei, ".jpg")) {
   $info = getimagesize($verzeichnis_datei);
   array_push($bilderliste, array(filemtime($verzeichnis_datei) , $verzeichnis_datei , $info[0] , $info[1]));
  }
 }
}
closeDir($handle);

rsort($bilderliste);

$icon .='


<table border="1"width="100%">
 <tr>
  <th>Bild</th> <th style="display:none">Name</th> <th>Datum</th> <th>Auswahl</th>
 </tr>

';
foreach ($bilderliste as $zaehler => $element) {
	
$icon .= "<tr>";
$icon .= "<td>" . $pfad . "" . $bilderliste[$zaehler][1] . "<br />
<a rel=''class='thickbox' href='" . $bilderliste[$zaehler][1] . "'>	  
	  <img src='" . $bilderliste[$zaehler][1] . "' width='105'height='61'  /></a> 
</td>";
$icon .= "<td style='display:none'><img src='" . $pfad . "" . $bilderliste[$zaehler][1] . "'width='105'height='61' /></td>";
$icon .= "<td>" . date("d.m.Y H:i", $bilderliste[$zaehler][0]) . "</td>";
 $name ="".$pfad."" . $bilderliste[$zaehler][1] . "";
 $filename = "".$pfad."" . $bilderliste[$zaehler][1] . "";
$icon .= "<td>
  
 <span>


<input type=\"checkbox\" name=\"c3\" id='ok'value=\"" . $bilderliste[$zaehler][1] . "\" onClick=\"icons(this.name, this.checked, this.value)\"> Auswählen</span>
<span>


<input  id='none'type=\"image\"src=\"system/delete_16.png\" name=\"c4\" value=\"\" onClick=\"icons(this.name, this.checked, this.value)\">aufheben</span></td>";
$icon .= "<td><form action='' target='_blank' method='POST'>
<div align='center'>

<input type='hidden' name='loeschen'value='" . str_replace($verzeichnis, "", $bilderliste[$zaehler][1]) . "'>
<input type='submit'onClick='return confirm(\"Wirklich " . str_replace($verzeichnis, "", $bilderliste[$zaehler][1]) . " löschen?\");' value='löschen'>
<input type='hidden' name='action' value='del'>
</div>
</form>

</td>";
$icon .= "</tr>";
}
$icon .= "</table>";	
	
	
	
	
	
	
	
	
	
	
	
//--------------------------------------------------------------------------------	

 
/**
* ausgabe ENDE!
*/					
			

?>