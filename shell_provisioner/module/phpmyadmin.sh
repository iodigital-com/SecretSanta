#!/bin/bash

# PhpMyAdmin

echo 'phpmyadmin phpmyadmin/dbconfig-install boolean true' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/app-password-confirm password vagrant' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/admin-pass password vagrant' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/app-pass password vagrant' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2' | debconf-set-selections
apt-get -y install phpmyadmin
