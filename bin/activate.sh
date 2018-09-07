#!/bin/bash

cd "$(dirname "$0")"
cd ../

# activate plugin menu
sed -i "s/{FEATUREBOX}/{XPANDSLIDER}/g" ../../e107_themes/bootstrap3/theme.php

echo ""
echo "###############################################"
echo "xpandSlider plugin menu activated!"
echo "###############################################"
echo ""
