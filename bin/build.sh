#!/bin/bash

cd "$(dirname "$0")"

# install dependencies
cd ../
yarn

rm -rvf assets
mkdir tmpassets
cp -rv node_modules/@bower_components/. tmpassets/
rm -rvf node_modules
mv tmpassets assets

mkdir build
zip -r build/xpandslider-$1.zip . -x \
".git/*" ".idea/*" "build/*" "assets/camera-slideshow/images/slides\ copy/*" "images/psd/*" \
"assets/camera-slideshow/images/slides/*" "assets/elfinder/js/elfinder.full.js"

echo ""
echo "###############################################"
echo "xpandSlider plugin builded!"
echo "###############################################"
echo ""

