#!/bin/bash

cd /vagrant

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
