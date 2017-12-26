#!/bin/bash

cd /vagrant

# Download GeoIP database
mkdir /usr/local/share/GeoIP
curl http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz > /tmp/geolite.tar.gz
tar xzf /tmp/geolite.tar.gz --strip=1 -C /usr/local/share/GeoIP GeoLite2-City_20171205/GeoLite2-City.mmdb

# Prepare Symfony
if [ ! -f app/config/parameters.yml ]; then
    cp app/config/parameters.yml.dist app/config/parameters.yml
fi

rm -rf /vagrant/app/{cache,logs}
mkdir /home/vagrant/{cache,logs}
ln -s /vagrant/app/cache /home/vagrant/cache
ln -s /vagrant/app/logs /home/vagrant/logs

# Composer
composer.phar install

php bin/console doctrine:schema:create
php bin/console assets:install

cd -
