#!/bin/bash

cd /vagrant

# Download GeoIP database
mkdir /usr/local/share/GeoIP
wget -q -O - http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz > /tmp/geolite.tar.gz
tar xzf /tmp/geolite.tar.gz --strip=1 -C /usr/local/share/GeoIP GeoLite2-City_20191008/GeoLite2-City.mmdb

rm -rf /vagrant/app/{cache,logs}
mkdir /home/vagrant/{cache,logs}
ln -s /vagrant/app/cache /home/vagrant/cache
ln -s /vagrant/app/logs /home/vagrant/logs

# Composer
composer.phar install

php bin/console doctrine:schema:create
php bin/console assets:install

# Install Symfony CLI
curl -sS https://get.symfony.com/cli/installer | bash -s -- --install-dir=/usr/local/bin

cd -
