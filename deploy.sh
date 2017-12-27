#!/bin/bash

set -x # xtrace: print commands
set -e # errexit: exit on error

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
composer.phar install --no-dev --classmap-authoritative

# Install assets
bin/console assets:install web
cp ../../shared/yandex_* web

# Cleanup
rm -rf .git .gitignore Vagrantfile shell_provisioner
rm -rf web/app_{dev,test,test_travis}.php web/config.php

# Reset permissions
sudo chmod -R ug=rwX,o=rX ../$VERSION
sudo chmod -R a+rwX var/logs var/cache

# Activate latest
sudo service php7.1-fpm stop
bin/console doctrine:schema:update --force --env=${SYMFONY_ENV}
cd ../..
ln -sfn releases/$VERSION current
sudo service php7.1-fpm start

# Cleanup old deployment, keep last 2
cd releases
ls -1 | sort -r | tail -n +3 | xargs sudo rm -rf
cd ..

echo SecretSanta version $VERSION is deployed!
