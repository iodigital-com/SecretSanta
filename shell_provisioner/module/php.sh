#!/bin/bash

# PHP

# Add deb.sury.org repository
wget -qO - https://packages.sury.org/php/apt.gpg | APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1 apt-key add -

cat << EOF >/etc/apt/sources.list.d/sury.list
deb https://packages.sury.org/php/ buster main
EOF

# Sync package index files
apt-get update

apt-get -y install php7.4-cli php7.4-fpm php7.4-dev php7.4-xml php7.4-intl php7.4-mysql php7.4-mbstring php7.4-curl

# PHP config
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.4/cli/php.ini
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.4/fpm/php.ini
sed -i 's/^user = www-data/user = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/^group = www-data/group = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf

# Install APCu
printf "\n" | pecl install apcu

cat << EOF >>/etc/php/7.4/mods-available/apcu.ini
extension=apcu.so
EOF

ln -s /etc/php/7.4/mods-available/apcu.ini /etc/php/7.4/cli/conf.d/20-apcu.ini
ln -s /etc/php/7.4/mods-available/apcu.ini /etc/php/7.4/fpm/conf.d/20-apcu.ini

# Install Xdebug (not released for 7.4 atm, build master from git)
cd /tmp
git clone https://github.com/xdebug/xdebug
cd xdebug
phpize
./configure
make
make install

cat << EOF >>/etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_host=192.168.33.1
xdebug.max_nesting_level=256
; xdebug.profiler_enable=1
; xdebug.profiler_output_dir=/vagrant/dumps
EOF

ln -s /etc/php/7.4/mods-available/xdebug.ini /etc/php/7.4/cli/conf.d/10-xdebug.ini
ln -s /etc/php/7.4/mods-available/xdebug.ini /etc/php/7.4/fpm/conf.d/10-xdebug.ini

# Reload FPM
service php7.4-fpm restart

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin
