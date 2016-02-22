#!/bin/bash

# Locales
sed -i 's/# nl_BE.UTF-8 UTF-8/nl_BE.UTF-8 UTF-8/' /etc/locale.gen
locale-gen
# echo 'LANG=nl_BE.UTF-8' > /etc/default/locale

# Timezone
echo "Europe/Brussels" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata

# dotdeb
cat << EOF >/etc/apt/sources.list.d/dotdeb.list
deb http://packages.dotdeb.org wheezy-php56 all
deb-src http://packages.dotdeb.org wheezy-php56 all
EOF
wget -qO - http://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -

# Custom bash prompt
echo "PS1='[\u@\h-\[\033[00;34m\]dev\[\033[00m\] \w]\n\\$ '" >> /etc/bash.bashrc
echo "PS1='[\u@\h-\[\033[00;34m\]dev\[\033[00m\] \w]\n\\$ '" >> /home/vagrant/.bashrc

# Console keyboard
sed -i 's/XKBLAYOUT=.*/XKBLAYOUT="be"/' /etc/default/keyboard
setupcon --force

# Sync package index files
apt-get update
