#!/usr/bin/env bash

cp /home/travis/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini ~/xdebug.ini
phpenv config-rm xdebug.ini || exit $? # Disable XDebug
mkdir -p \"${BUILD_CACHE_DIR}\" || exit $? # Create build cache directory

# Update composer to the latest stable release as the build env version is outdated
composer self-update --stable || exit $?
