#!/usr/bin/env bash

echo "Install dependencies"

composer install --no-interaction --prefer-dist -o || exit $?
