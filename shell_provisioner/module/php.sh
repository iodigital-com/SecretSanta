#!/bin/bash

# PHP

# Add deb.sury.org repository
wget -qO - https://packages.sury.org/php/apt.gpg | APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1 apt-key add -

cat << EOF >/etc/apt/sources.list.d/sury.list
deb https://packages.sury.org/php/ buster main
EOF

# Sync package index files
apt-get update

apt-get -y install php7.4-cli php7.4-fpm php7.4-dev php7.4-xml php7.4-intl php7.4-mysql php7.4-mbstring php7.4-curl \
    php-apcu php-xdebug

# PHP config
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.4/cli/php.ini
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.4/fpm/php.ini
sed -i 's/^user = www-data/user = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/^group = www-data/group = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf

# Configure apc
find /etc/php/7.4 -name 25-apcu_bc.ini -delete

# Configure xdebug
cat << EOF >/etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug

xdebug.mode=debug
xdebug.start_with_request=1
xdebug.client_host=192.168.33.1
xdebug.max_nesting_level=256
; xdebug.profiler_enable=1
; xdebug.profiler_output_dir=/vagrant/dumps
EOF
# Disable xdebug on CLI by default
echo "export XDEBUG_MODE=off" >> /home/vagrant/.bashrc

# Reload FPM
service php7.4-fpm restart

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin
