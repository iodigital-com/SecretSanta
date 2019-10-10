#!/bin/bash

# PhpMyAdmin

# Download and extract
PMA_VERSION=4.9.0.1
cd /var/www
wget -q https://files.phpmyadmin.net/phpMyAdmin/${PMA_VERSION}/phpMyAdmin-${PMA_VERSION}-all-languages.tar.gz
tar xzf phpMyAdmin-${PMA_VERSION}-all-languages.tar.gz
mv phpMyAdmin-${PMA_VERSION}-all-languages phpmyadmin
rm phpMyAdmin-${PMA_VERSION}-all-languages.tar.gz
chown -R vagrant:vagrant phpmyadmin
chmod -R ug+rwX phpmyadmin

# Configure
cd phpmyadmin
cp config.sample.inc.php config.inc.php
sed -e '/controluser/ s/^\/\/ *//' -i config.inc.php
sed -e '/controlpass/ s/^\/\/ *//' -i config.inc.php
mysql -uroot -pvagrant -e "CREATE DATABASE phpmyadmin DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"
mysql -uroot -pvagrant -e "GRANT ALL PRIVILEGES ON phpmyadmin.* TO pma@localhost IDENTIFIED BY 'pmapass';"
mysql -uroot -pvagrant < sql/create_tables.sql

# Add Nginx vhost
cat ${CONFIG_PATH}/apache/phpmyadmin.${APP_DOMAIN}.conf > /etc/apache2/sites-available/phpmyadmin.${APP_DOMAIN}.conf

a2ensite phpmyadmin.${APP_DOMAIN}
service apache2 restart
