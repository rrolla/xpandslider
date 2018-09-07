#!/bin/bash

cd "$(dirname "$0")"

# uninstall dependencies
cd ../
mv -v ../../e107_web/lib/{elfinder,camera-slideshow} assets/
rm -rvf assets

echo ""
echo "###############################################"
echo "xpandSlider plugin dependencies uninstalled!"
echo "###############################################"
echo ""
