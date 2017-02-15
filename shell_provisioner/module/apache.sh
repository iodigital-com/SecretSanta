#!/bin/bash

# Apache

apt-get install -y apache2

a2enmod rewrite expires headers proxy proxy_http proxy_fcgi ssl

a2dissite 000-default
echo "ServerTokens Prod" >>/etc/apache2/apache2.conf
echo "TraceEnable Off" >>/etc/apache2/apache2.conf
echo "FileETag None" >>/etc/apache2/apache2.conf

sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
chown -R vagrant:www-data /var/lock/apache2
chmod -R a+rX /var/log/apache2
sed -i 's/640/666/' /etc/logrotate.d/apache2
sed -i 's/*:80/192.168.33.50:80/' /etc/apache2/ports.conf
sed -i 's/Listen 80/Listen 192.168.33.50:80/' /etc/apache2/ports.conf

cat ${CONFIG_PATH}/apache/${APP_DOMAIN}.conf > /etc/apache2/sites-available/${APP_DOMAIN}.conf

a2ensite ${APP_DOMAIN}
service apache2 restart
