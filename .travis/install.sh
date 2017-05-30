#!/usr/bin/env bash

echo "Install dependencies"

composer install --no-interaction --no-scripts --prefer-dist --classmap-authoritative || exit $?

echo "Warming up dependencies"
composer run-script travis-build --no-interaction || exit $?
