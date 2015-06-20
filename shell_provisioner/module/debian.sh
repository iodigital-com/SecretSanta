#!/bin/bash

# Locales
sed -i 's/# nl_BE.UTF-8 UTF-8/nl_BE.UTF-8 UTF-8/' /etc/locale.gen
locale-gen
# echo 'LANG=nl_BE.UTF-8' > /etc/default/locale

# Timezone
echo "Europe/Brussels" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata

# Custom bash prompt
echo "PS1='[\u@\h-\[\033[00;34m\]dev\[\033[00m\] \w]\n\\$ '" >> /etc/bash.bashrc
echo "PS1='[\u@\h-\[\033[00;34m\]dev\[\033[00m\] \w]\n\\$ '" >> /home/vagrant/.bashrc

# Console keyboard
sed -i 's/XKBLAYOUT=.*/XKBLAYOUT="be"/' /etc/default/keyboard
setupcon --force

# Sync package index files
apt-get update
