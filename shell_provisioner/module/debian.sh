#!/bin/bash

# Locales
sed -i 's/# nl_BE.UTF-8 UTF-8/nl_BE.UTF-8 UTF-8/' /etc/locale.gen
locale-gen

# Timezone
echo $TIMEZONE > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata

# Custom bash prompt
echo "PS1='[\[\033[00;34m\]\u@secretsanta DEV \[\033[00;31m\]\w$(__git_ps1)\[\033[00m\]]\n\\$ '" >> /etc/bash.bashrc
echo "PS1='[\[\033[00;34m\]\u@secretsanta DEV \[\033[00;31m\]\w$(__git_ps1)\[\033[00m\]]\n\\$ '" >> /home/vagrant/.bashrc

# Host file
echo 127.0.0.1 $APP_DOMAIN >> /etc/hosts
echo 127.0.0.1 phpmyadmin.$APP_DOMAIN >> /etc/hosts
echo 127.0.0.1 mailcatcher.$APP_DOMAIN >> /etc/hosts

# Sync package index files
apt-get update
apt-get install -y apt-transport-https lsb-release ca-certificates

# XXX: this is a workaround to fix some install problems later with percona-toolkit and mailutils
apt-get install mysql-common
rm -rf /etc/mysql

