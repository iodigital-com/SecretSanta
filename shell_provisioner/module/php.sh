#!/bin/bash

# PHP

apt-get -y install php7.0-cli php7.0-fpm php7.0-dev php7.0-xdebug \
    php7.0-xml php7.0-intl php7.0-mysqlnd php7.0-apcu php7.0-mbstring php7.0-curl

# PHP config
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.0/cli/php.ini
sed -i "s#;date.timezone =#date.timezone = ${TIMEZONE}#" /etc/php/7.0/fpm/php.ini
sed -i 's/^user = www-data/user = vagrant/' /etc/php/7.0/fpm/pool.d/www.conf
sed -i 's/^group = www-data/group = vagrant/' /etc/php/7.0/fpm/pool.d/www.conf

cat << EOF >>/etc/php/7.0/mods-available/xdebug.ini
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_host=192.168.33.1
xdebug.max_nesting_level=256
; xdebug.profiler_enable=1
; xdebug.profiler_output_dir=/vagrant/dumps
EOF

service php7.0-fpm restart

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin

# phpunit
wget -P /usr/bin https://phar.phpunit.de/phpunit.phar
chmod +x /usr/bin/phpunit.phar
ln -s /usr/bin/phpunit.phar /usr/bin/phpunit
