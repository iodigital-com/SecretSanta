#!/bin/bash

# Shell provisioner

export DEBIAN_FRONTEND=noninteractive

MODULE_PATH='/vagrant/shell_provisioner/module'
CONFIG_PATH='/vagrant/shell_provisioner/config'

TIMEZONE='Europe/Brussels'
APP_DOMAIN='dev.secretsantaorganizer.com'

DEPENDENCIES=(
    debian
    tools
    vim
    php
    mysql
    openssl
    apache
    phpmyadmin
    symfony
    yarn
    mailhog
    rsyslogd
)

for MODULE in ${DEPENDENCIES[@]}; do
    source ${MODULE_PATH}/${MODULE}.sh
done

