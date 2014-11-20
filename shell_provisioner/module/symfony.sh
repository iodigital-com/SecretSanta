#!/bin/bash

cd /vagrant

if [ ! -f app/config/parameters.yml ]; then
    cp app/config/parameters.yml.dist app/config/parameters.yml
fi

# Composer
composer.phar install

php app/console doctrine:schema:create
php app/console assets:install
php app/console assetic:dump

cd -
