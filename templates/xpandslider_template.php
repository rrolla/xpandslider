<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2018 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * xpandSlider plugin - Perfect responsive image, HTML slider for e107 CMS
 * Author: rolla <raitis.rolis@gmail.com>
 *
*/

$XPANDSLIDER_TEMPLATE['start'] = '<div class="slider-container">
                                  <div class="slides {XPANDSLIDER_CAMERASKIN}">';
$XPANDSLIDER_TEMPLATE['item'] = '<div
                                  class="slide"
                                  data-slide="{XPANDSLIDER_ID}"
                                  data-src="' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . '{XPANDSLIDER_IMAGE}"
                                  {XPANDSLIDER_DATA}>
                                  <div class="{XPANDSLIDER_CAPTIONFX} camera_caption">{XPANDSLIDER_CAPTION}</div>
                                  {XPANDSLIDER_CONTENT}
                                </div>';
$XPANDSLIDER_TEMPLATE['end'] = '  </div>
                                </div>
                                <script>
                                  var cameraOptions = {XPANDSLIDER_CAMERAOPTIONS};
                                  $(function () {
                                    $("div.slides").camera(cameraOptions);
                                  });
                                </script>
';
