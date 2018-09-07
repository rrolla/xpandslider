#!/bin/bash

cd "$(dirname "$0")"
cd ../

# deactivate plugin menu
sed -i "s/{XPANDSLIDER}/{FEATUREBOX}/g" ../../e107_themes/bootstrap3/theme.php

echo ""
echo "###############################################"
echo "xpandSlider plugin menu deactivated!"
echo "###############################################"
echo ""
