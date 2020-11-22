#!/usr/bin/env bash

echo "Install dependencies"
composer install --no-interaction --no-scripts --prefer-dist --classmap-authoritative || exit $?

echo "Install local .env"
cat << EOF > .env.test.local
DATABASE_URL="mysql://root@127.0.0.1/secretsanta_test?serverVersion=5.7&charset=utf8mb4"
GEO_IP_DB_PATH=$BUILD_CACHE_DIR/GeoLite2-City.mmdb
EOF

echo "building frontend"
cp config/recaptcha_secrets.json.dist config/recaptcha_secrets.json
yarn
yarn build
