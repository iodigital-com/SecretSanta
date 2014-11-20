#!/bin/bash

# PHP

apt-get -y install php5 php5-intl php5-xdebug

# PHP config
sed -i "s#;date.timezone =#date.timezone = Europe/Brussels#" /etc/php5/apache2/php.ini
sed -i "s#;date.timezone =#date.timezone = Europe/Brussels#" /etc/php5/cli/php.ini

# xdebug
cat << EOF >>/etc/php5/mods-available/xdebug.ini
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_host=192.168.33.1
xdebug.max_nesting_level=250
; xdebug.profiler_enable=1
; xdebug.profiler_output_dir=/vagrant/dumps
EOF

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin

# phpunit
cd /usr/bin
wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
cd -
