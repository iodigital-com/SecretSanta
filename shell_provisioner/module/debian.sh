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
echo 127.0.0.1 mails.$APP_DOMAIN >> /etc/hosts

# Configure motd
cat << EOF >/etc/update-motd.d/50-secretsanta
#!/bin/bash
cat << EOFF
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Hi Secret Santa hacker!

 - Set "XDEBUG_MODE=debug" on your commandline to start debugging php scripts.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
EOFF
EOF
chmod a+x /etc/update-motd.d/50-secretsanta
cat /dev/null > /etc/motd

# Sync package index files
apt-get update
apt-get install -y apt-transport-https lsb-release ca-certificates
apt-get dist-upgrade
