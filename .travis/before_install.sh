#!/usr/bin/env bash

#sudo apt install -y libmemcached-dev
git clone https://github.com/php-memcached-dev/php-memcached.git
cd php-memcached
phpize
./configure --disable-memcached-sasl
make
sudo make install
echo "extension = memcached" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini

git clone https://github.com/xdebug/xdebug
cd xdebug
git checkout origin/xdebug_2_9 -b 2.9
phpize
./configure
make
make install
cat << EOF >~/xdebug.ini
zend_extension = xdebug
xdebug.coverage_enable=On
EOF

mkdir -p \"${BUILD_CACHE_DIR}\" || exit $? # Create build cache directory

# Download and configure geoip db (if you know where to download this in a proper way, please let me know)
if [ ! -f $BUILD_CACHE_DIR/GeoLite2-City.mmdb ]; then
    curl https://www.secretsantaorganizer.com/GeoLite2-City.mmdb > GeoLite2-City.mmdb
    mv GeoLite2-City.mmdb $BUILD_CACHE_DIR
fi

cp app/config/recaptcha_secrets.json.dist app/config/recaptcha_secrets.json

# Update composer to the latest stable release as the build env version is outdated
composer self-update --stable || exit $?
