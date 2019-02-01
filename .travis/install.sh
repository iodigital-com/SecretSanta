#!/usr/bin/env bash

echo "Install dependencies"
composer install --no-interaction --no-scripts --prefer-dist --classmap-authoritative || exit $?

echo "Warming up dependencies"
composer run-script travis-build --no-interaction || exit $?

echo "building frontend"
cat << EOF >app/config/client_secrets.json
{
    "key": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "secret_key": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "action": "contact",
    "threshold": 0.5
}
EOF
yarn
yarn build
