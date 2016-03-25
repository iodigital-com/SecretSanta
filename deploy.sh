#!/bin/bash

set -o xtrace # print commands
set -e # exit on error

PROJECT_HOME=/var/www/secretsanta
VERSION=`date +"%Y%m%d%H%M%S"`
export SYMFONY_ENV=prod
cd $PROJECT_HOME

# Get latest version
cd git
git pull origin master
cd ..

# Deploy latest version
cp -R git releases/$VERSION
cd releases/$VERSION

cp ../../shared/parameters.yml app/config
cp ../../shared/client_secrets.json app/config
ln -s ../../export
composer.phar install --no-dev --optimize-autoloader
app/console doctrine:schema:update --force --env=${SYMFONY_ENV}

# Install assets
app/console assets:install web
app/console assetic:dump --env=${SYMFONY_ENV} --no-debug
cp ../../shared/yandex_* web

# Cleanup
rm -rf .git .gitignore Vagrantfile shell_provisioner
rm -rf web/app_dev.php web/config.php

# Reset permissions
sudo chown -R www-data:www-data ../$VERSION
sudo chmod -R ug=rwX,o= ../$VERSION
sudo chmod -R a+rwX app/logs app/cache

cd ../..

# Activate latest
ln -sfn releases/$VERSION current
sudo service apache2 restart

# Cleanup old deployment, keep last 2
cd releases
ls -1 | sort -r | tail -n +3 | xargs sudo rm -rf
cd ..

echo SecretSanta version $VERSION is deployed!
