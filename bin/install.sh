#!/bin/bash

cd "$(dirname "$0")"

# install dependencies
cd ../
git clone -b 2.1 --single-branch https://github.com/Studio-42/elFinder.git assets/elfinder
yarn
mv -v assets/* ../../e107_web/lib/

# fix permissions
# chmod -vR 2775 images/slides

echo ""
echo "###############################################"
echo "xpandSlider plugin dependencies installed!"
echo "###############################################"
echo ""

# go to e107 root
# cd ../../
# pwd
