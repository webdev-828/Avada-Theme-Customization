#!/usr/bin/env bash

# move 1 folder back and create a new sub-folder
echo "Creating folder in ../avada-release-pack"
cd ..
rm -Rf avada-release-pack
mkdir avada-release-pack

# go to the new subfolder and pull Avada, Fusion-Core & Localization-l10n
cd avada-release-pack
echo "Cloning Avada..."
git clone -q git@github.com:Theme-Fusion/Avada.git
echo "Cloning Fusion-Core..."
git clone -q git@github.com:Theme-Fusion/Fusion-Core.git fusion-core
echo "Cloning Languages..."
git clone -q git@github.com:Theme-Fusion/Localization-l10n.git languages

# grunt Avada
echo "Installing & running Grunt on Avada..."
cd Avada
npm install &>/dev/null
grunt &>/dev/null
grunt googlefonts &>/dev/null
cd ..

# grunt Fusion-Core
echo "Installing & running Grunt on Fusion-Core..."
cd fusion-core
npm install &>/dev/null
grunt &>/dev/null
cd ..

# copy the new pot files to the languages folder
echo "Copying language files from Avada & Fusion-Core to the languages folder..."
cp Avada/languages/Avada.pot languages/Avada/Avada.pot
cp fusion-core/languages/fusion-core.pot languages/fusion-core.pot

# remove unnecessary files from Avada
echo "Removing unnecessary files from Avada..."
rm -rf Avada/.git &>/dev/null
rm -rf Avada/node_modules &>/dev/null
rm -rf Avada/.sass-cache &>/dev/null
rm -f Avada/.DS_Store &>/dev/null
rm -f Avada/.editorconfig &>/dev/null
rm -f Avada/.gitignore &>/dev/null
rm -f Avada/.gitmodules &>/dev/null
rm -f Avada/build-release.sh &>/dev/null

echo "Deleting the fusion-core.zip file from Avada. Don't worry, we'll add it again later."
# delete the fusion-core zip file. We'll add a fresh copy later
rm -f Avada/includes/plugins/fusion-core.zip &>/dev/null

# remove unnecessary files from fusion-core
echo "Removing unnecessary files from Fusion-Core..."
rm -rf fusion-core/.git &>/dev/null
rm -rf fusion-core/node_modules &>/dev/null
rm -f fusion-core/.editorconfig &>/dev/null
rm -f fusion-core/.gitignore &>/dev/null
rm -f fusion-core/.DS_Store &>/dev/null

# fix permissions
echo "Fixing File & Folder permissions..."
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# ZIP fusion-core
echo "ZIPing fusion-core..."
zip -rq fusion-core.zip fusion-core

# we no longer need the fusion-core folder, delete it.
echo "Deleting the fusion-core folder..."
rm -Rf fusion-core

# move the zipped fusion-core plugin in Avada
echo "Moving the fusion-core.zip file in Avada..."
mv fusion-core.zip Avada/includes/plugins/fusion-core.zip
# make sure the file has the right permissions
echo "Fixing fusion-core.zip permissions"
chmod 644 Avada/includes/plugins/fusion-core.zip

# ZIP Avada
echo "ZIPing the Avada theme..."
zip -rq Avada.zip Avada

# we no longer need the Avada folder, delete it.
echo "deleting the Avada folder, we no longer need it..."
rm -Rf Avada

# Move to the languages folder and run grunt to regenerate all other languages
cd languages
# echo "Replacing email address with contact@theme-fusion.com"
# sed -i 's/EMAIL@ADDRESS/contact@theme-fusion.com/g' Avada/Avada.pot
# sed -i 's/EMAIL@ADDRESS/contact@theme-fusion.com/g' fusion-core/fusion-core.pot

echo "Installing & running grunt in the languages folder..."
npm install &>/dev/null
# hack to make the packages run on OSX
# brew link gettext --force &>/dev/null
grunt &>/dev/null

# commit & push new language files
echo "committing & pushing new languages to the github repository..."
git add . &>/dev/null
git commit -a -m "updating language files" &>/dev/null
git push origin master &>/dev/null

echo "Deleting the languages folder, it's no longer needed..."
cd ..
rm -Rf languages

echo "DONE!"
echo "You can get the final Avada.zip file from the ../avada-release-pack folder."
