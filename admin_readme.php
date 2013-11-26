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
require_once(e_ADMIN."auth.php");
$articulate_text ='<style type="text/css">
<!--
body.hl	{ background-color:#ffffff; }
pre.hl	{ color:#000000; background-color:#ffffff; font-size:10pt; font-family:\'Courier New\';}
.hl.num { color:#ff8000; }
.hl.esc { color:#808080; }
.hl.str { color:#808080; }
.hl.dstr { color:#000000; }
.hl.slc { color:#008000; }
.hl.com { color:#008080; }
.hl.dir { color:#804000; }
.hl.sym { color:#000080; font-weight:bold; }
.hl.line { color:#555555; }
.hl.mark	{ background-color:#000000;}
.hl.kwa { color:#0000ff; font-weight:bold; }
.hl.kwb { color:#8000ff; }
.hl.kwc { color:#8000ff; }
.hl.kwd { color:#000000; }
//-->
</style>
<table class="fborder" >
	<tr>
		<td>shortcode für deine theme.php  ist in dieser version noch nicht enthalten

<pre class="hl">shortcode für deine theme.php

$register_sc[]=\'slider\';

und im body an der stelle wo er erscheinen soll

{slider}
</pre>

und im body an der stelle wo er erscheinen soll

{slider}</td>
	</tr>
	
</table>';

#readme;


$ns->tablerender(Readme, $articulate_text);

require_once(e_ADMIN."footer.php");


?>