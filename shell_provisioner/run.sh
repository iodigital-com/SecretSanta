#!/bin/bash

# Shell provisioner

MODULE_PATH='/vagrant/shell_provisioner/module'
CONFIG_PATH='/vagrant/shell_provisioner/config'

DEPENDENCIES=(
    debian
    tools
    vim
    php
    mysql
    apache
    phpmyadmin
    symfony
    roundcube
)

for MODULE in ${DEPENDENCIES[@]}; do
    source ${MODULE_PATH}/${MODULE}.sh
done
