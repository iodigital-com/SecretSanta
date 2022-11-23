#!/bin/bash

# PHP

# Add deb.sury.org repository
wget -qO - https://packages.sury.org/php/apt.gpg | APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1 apt-key add -

cat << EOF >/etc/apt/sources.list.d/sury.list
deb https://packages.sury.org/php/ bullseye main
EOF

# Sync package index files
apt-get update

apt-get -y install php8.0-cli php8.0-fpm php8.0-dev php8.0-xml php8.0-intl php8.0-mysql php8.0-mbstring php8.0-curl \
    php8.0-apcu php8.0-xdebug

update-alternatives --set php /usr/bin/php8.0

# PHP config
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/8.0/cli/php.ini
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/8.0/fpm/php.ini
sed -i 's/^user = www-data/user = vagrant/' /etc/php/8.0/fpm/pool.d/www.conf
sed -i 's/^group = www-data/group = vagrant/' /etc/php/8.0/fpm/pool.d/www.conf

# Configure apc
find /etc/php/8.0 -name 25-apcu_bc.ini -delete

# Configure xdebug
cat << EOF >/etc/php/8.0/mods-available/xdebug.ini
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
service php8.0-fpm restart

# composer
curl -sS -o /usr/bin/composer https://getcomposer.org/composer.phar
chmod +x /usr/bin/composer
composer self-update
