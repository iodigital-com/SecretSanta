#!/bin/bash

# PHP

# Add deb.sury.org repository
wget -O- https://packages.sury.org/php/apt.gpg | apt-key add -

cat << EOF >/etc/apt/sources.list.d/sury.list
deb https://packages.sury.org/php/ stretch main
EOF

# Sync package index files
apt-get update

apt-get -y install php7.3-cli php7.3-fpm php7.3-dev php7.3-xml php7.3-intl php7.3-mysql php7.3-mbstring php7.3-curl

# PHP config
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.3/cli/php.ini
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.3/fpm/php.ini
sed -i 's/^user = www-data/user = vagrant/' /etc/php/7.3/fpm/pool.d/www.conf
sed -i 's/^group = www-data/group = vagrant/' /etc/php/7.3/fpm/pool.d/www.conf

# Install APCu
printf "\n" | pecl install apcu

cat << EOF >>/etc/php/7.3/mods-available/apcu.ini
extension=apcu.so
EOF

ln -s /etc/php/7.3/mods-available/apcu.ini /etc/php/7.3/cli/conf.d/20-apcu.ini
ln -s /etc/php/7.3/mods-available/apcu.ini /etc/php/7.3/fpm/conf.d/20-apcu.ini

# Install Xdebug (not released for 7.3 atm, build master from git)
cd /tmp
git clone https://github.com/xdebug/xdebug
cd xdebug
phpize
./configure
make
make install

PHP_API=`php -i | grep "PHP API => " | cut -d' ' -f4`

cat << EOF >>/etc/php/7.3/mods-available/xdebug.ini
zend_extension=/usr/lib/php/${PHP_API}/xdebug.so
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_host=192.168.33.1
xdebug.max_nesting_level=256
; xdebug.profiler_enable=1
; xdebug.profiler_output_dir=/vagrant/dumps
EOF

ln -s /etc/php/7.3/mods-available/xdebug.ini /etc/php/7.3/cli/conf.d/10-xdebug.ini
ln -s /etc/php/7.3/mods-available/xdebug.ini /etc/php/7.3/fpm/conf.d/10-xdebug.ini

# Reload FPM
service php7.3-fpm restart

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin

# phpunit
wget -P /usr/bin https://phar.phpunit.de/phpunit.phar
chmod +x /usr/bin/phpunit.phar
ln -s /usr/bin/phpunit.phar /usr/bin/phpunit
