#!/bin/bash

read -p "Mediawiki Release [REL1_31]:" RELEASE
RELEASE=${RELEASE:-REL1_31}

read -p "System user [www-data]:" USER
USER=${USER:-www-data}

# Install Mediawiki  and import configuration files
git clone  --recurse-submodules https://gerrit.wikimedia.org/r/p/mediawiki/core.git --branch ${RELEASE} --depth=1 v2.lingualibre.fr
cd v2.lingualibre.fr/
wget https://raw.githubusercontent.com/lingua-libre/operations/master/mediawiki-config/ll.png -o /resources/assets/ll.png
wget https://raw.githubusercontent.com/lingua-libre/operations/master/mediawiki-config/LocalSettings.php
mkdir private
wget https://raw.githubusercontent.com/lingua-libre/operations/master/mediawiki-config/private/PrivateSettings.php.sample -o private/PrivateSettings.php
chown -R ${USER}:${USER} ./
sudo -u ${USER} composer install

# Install skins
sudo -u ${USER} git clone --depth 1 https://github.com/lingua-libre/llskin.git skins/foreground

# Install extensions
cd extensions/
for ext in OAuthAuthentication Wikibase cldr CleanChanges LocalisationUpdate Babel UniversalLanguageSelector Translate MwEmbedSupport TimedMediaHandler
do
	sudo -u ${USER} git clone https://gerrit.wikimedia.org/r/p/mediawiki/extensions/${ext}.git --branch ${RELEASE} --depth 1
done

# Install Lingua Libre specific extensions
for ext in QueryViz RecordWizard Upload2Commons
do
	sudo -u ${USER} git clone https://github.com/lingua-libre/${ext}.git --depth 1
done


# Use patched version of OAuthAuthentication
cd OAuthAuthentication/ && sudo -u ${USER} git fetch https://gerrit.wikimedia.org/r/mediawiki/extensions/OAuthAuthentication refs/changes/30/251930/25 && sudo -u ${USER} git checkout FETCH_HEAD && cd ../

# Import submodules and install dependencies of Wikibase
cd Wikibase && sudo -u ${USER} git submodule update --init --recursive cd ../

# Composer install
for ext in Wikibase OAuthAuthentication TimedMediaHandler
do
	cd ${ext}/
	sudo -u ${USER} composer install --no-dev
	cd ../
done

# Npm install
for ext in QueryViz RecordWizard
do
	cd ${ext}/
	sudo -u ${USER} npm install --production
	cd ../
done

# Use a patched version of oauthclient-php
cd OAuthAuthentication/vendor/mediawiki/ && rm -r oauthclient && sudo -u ${USER} git clone https://gerrit.wikimedia.org/r/mediawiki/oauthclient-php oauthclient && cd oauthclient && sudo -u ${USER} git fetch https://gerrit.wikimedia.org/r/mediawiki/oauthclient-php refs/changes/53/408853/2 && sudo -u ${USER} git checkout FETCH_HEAD && cd ../../../../

# Run maintenance scripts
cd ../
php maintenance/update.php
php extensions/Wikibase/lib/maintenance/populateSitesTable.php
php extensions/Wikibase/repo/maintenance/rebuildItemsPerSite.php

