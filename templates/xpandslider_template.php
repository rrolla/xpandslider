<?php

$XPANDSLIDER_TEMPLATE['start'] = '<div class="slider-container">
                                  <div class="slides {XPANDSLIDER_CAMERASKIN}">';
$XPANDSLIDER_TEMPLATE['item'] = '<div
                                  class="slide"
                                  data-slide="{XPANDSLIDER_ID}"
                                  data-src="' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . '{XPANDSLIDER_IMAGE}"
                                  data-thumb="' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR .'{XPANDSLIDER_THUMB}"
                                  {XPANDSLIDER_DATA}>
                                  <div class="{XPANDSLIDER_CAPTIONFX} camera_caption">{XPANDSLIDER_TITLE}</div>
                                  {XPANDSLIDER_CONTENT}
                                </div>';
$XPANDSLIDER_TEMPLATE['end'] = '  </div>
                                </div>';
