#!/bin/bash

# Shell provisioner

export DEBIAN_FRONTEND=noninteractive

MODULE_PATH='/app/shell_provisioner/module'
CONFIG_PATH='/app/shell_provisioner/config'

TIMEZONE='Europe/Brussels'
APP_DOMAIN='dev.secretsantaorganizer.com'

DEPENDENCIES=(
    rsyslogd
    geoip
)

for MODULE in ${DEPENDENCIES[@]}; do
    source ${MODULE_PATH}/${MODULE}.sh
done
