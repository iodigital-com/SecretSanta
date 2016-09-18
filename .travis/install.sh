#!/usr/bin/env bash

echo "Install dependencies"

composer install --no-interaction --no-scripts --prefer-dist -o || exit $?
