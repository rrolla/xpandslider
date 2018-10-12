#!/bin/bash

cd "$(dirname "$0")"

# install dependencies
cd ../
yarn

mkdir tmpassets
rm -rvf assets
cp -rv node_modules/@bower_components/. tmpassets/
rm -rvf node_modules
mv tmpassets assets

# cleaning
rm -rvf assets/camera-slideshow/images/slides
rm -rvf assets/camera-slideshow/images/slides\ copy
rm assets/elfinder/js/elfinder.full.js
rm -rvf images/psd

mkdir build
zip -r build/xpandslider-$1.zip . -x ".git/*" ".idea/*" "build/*"

echo ""
echo "###############################################"
echo "xpandSlider plugin builded!"
echo "###############################################"
echo ""

