#!/bin/bash

# Shell provisioner

MODULE_PATH='/vagrant/shell_provisioner/module'
CONFIG_PATH='/vagrant/shell_provisioner/config'

TIMEZONE='Europe/Brussels'
APP_DOMAIN='dev.secretsantaorganizer.com'

DEPENDENCIES=(
    debian
    tools
    vim
    php
#    mysql
#    openssl
#    apache
#    phpmyadmin
#    symfony
#    mailcatcher
)

for MODULE in ${DEPENDENCIES[@]}; do
    source ${MODULE_PATH}/${MODULE}.sh
done

