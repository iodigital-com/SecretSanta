#!/bin/bash

function printAction {
    if [ "$DBG" != 1 ]; then
        let DOTS=50-${#1}
        echo -n "$1 " >&2
        printf '%0.s.' $(seq ${DOTS}) >&2
        echo -n " " >&2
    fi
}

function printOk {
    if [ "$DBG" != 1 ]; then
        echo -e "\033[32mOK\033[0m" >&2
    fi
}

Q='-q'
if [ "$DBG" = 1 ]; then
    set -x # xtrace: print commands
    Q=''
else
    exec > /dev/null
fi
set -e # errexit: exit on error

PROJECT_HOME=/var/www/secretsanta
VERSION=`date +"%Y%m%d%H%M%S"`
cd ${PROJECT_HOME}

printAction "Get latest version from Git"
cd git
git pull ${Q} origin master
cd ..
printOk

printAction "Install latest version"
cp -R git releases/${VERSION}
cd releases/${VERSION}

cp ../../shared/env.local .env
cp ../../shared/client_secrets.json config
cp ../../shared/recaptcha_secrets.json config
printOk

printAction "Composer install"
../../shared/composer.phar install --no-dev --classmap-authoritative ${Q}
printOk

printAction "Building frontend"
yarn && yarn build
printOk

printAction "Install assets"
cp ../../shared/yandex_* public
cp ../../shared/ads.txt public
cp ../../shared/GeoLite2-City.mmdb public
printOk

printAction "Cleanup files and setting permissions"
rm -rf .git .gitignore Vagrantfile shell_provisioner
rm -rf public/index_test.php
sudo chmod -R ug=rwX,o=rX ../${VERSION}
sudo chmod -R a+rwX var/log var/cache
printOk

printAction "Stopping FPM"
sudo systemctl stop php7.4-fpm
printOk
printAction "Running doctrine schema update"
bin/console doctrine:schema:update --force ${Q}
cd ../..
printOk
printAction "Activate new version"
ln -sfn releases/${VERSION} current
printOk
printAction "Starting FPM"
sudo systemctl start php7.4-fpm
printOk

printAction "Cleanup old versions, keep last 2"
cd releases
ls -1 | sort -r | tail -n +3 | xargs sudo rm -rf
cd ..
printOk

echo SecretSanta version ${VERSION} is deployed! >&2

