#!/usr/bin/env bash

mkdir -p \"${BUILD_CACHE_DIR}\" || exit $? # Create build cache directory

# Update composer to the latest stable release as the build env version is outdated
composer self-update --stable || exit $?
