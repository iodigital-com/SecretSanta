#!/bin/bash

# PHP

# Add deb.sury.org repository
wget -O- https://packages.sury.org/php/apt.gpg | apt-key add -

cat << EOF >/etc/apt/sources.list.d/sury.list
deb https://packages.sury.org/php/ jessie main
EOF

# Sync package index files
apt-get update

apt-get -y install php7.1-cli php7.1-fpm php7.1-dev php7.1-xml php7.1-intl php7.1-mysql php7.1-mbstring php7.1-curl

# PHP config
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.1/cli/php.ini
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.1/fpm/php.ini
sed -i 's/^user = www-data/user = vagrant/' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/^group = www-data/group = vagrant/' /etc/php/7.1/fpm/pool.d/www.conf

# Install APCu
printf "\n" | pecl install apc

cat << EOF >>/etc/php/7.1/mods-available/apcu.ini
extension=apcu.so
EOF

ln -s /etc/php/7.1/mods-available/apcu.ini /etc/php/7.1/cli/conf.d/20-apcu.ini
ln -s /etc/php/7.1/mods-available/apcu.ini /etc/php/7.1/fpm/conf.d/20-apcu.ini

# Install Xdebug
pecl install xdebug

PHP_API=`php -i | grep "PHP API => " | cut -d' ' -f4`

cat << EOF >>/etc/php/7.1/mods-available/xdebug.ini
zend_extension=/usr/lib/php/${PHP_API}/xdebug.so
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_host=192.168.33.1
xdebug.max_nesting_level=256
; xdebug.profiler_enable=1
; xdebug.profiler_output_dir=/vagrant/dumps
EOF

ln -s /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/cli/conf.d/10-xdebug.ini
ln -s /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/fpm/conf.d/10-xdebug.ini

# Reload FPM
service php7.1-fpm restart

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin

# phpunit
wget -P /usr/bin https://phar.phpunit.de/phpunit.phar
chmod +x /usr/bin/phpunit.phar
ln -s /usr/bin/phpunit.phar /usr/bin/phpunit
