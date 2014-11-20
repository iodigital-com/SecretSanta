#!/bin/bash

# MySQL

echo "mysql-server-5.5 mysql-server/root_password password vagrant" | debconf-set-selections
echo "mysql-server-5.5 mysql-server/root_password_again password vagrant" | debconf-set-selections
apt-get -y install mysql-server-5.5

sed -i "s/\[mysqld\]/[mysqld]\ninnodb_file_per_table = 1/" /etc/mysql/my.cnf
sed -i 's/bind-address.*/bind-address\t\t= 0.0.0.0/' /etc/mysql/my.cnf
service mysql restart

# Add database
MYSQLCMD="mysql -uroot -pvagrant -e"
$MYSQLCMD "CREATE DATABASE secretsanta DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"

$MYSQLCMD "CREATE USER vagrant@localhost IDENTIFIED BY 'vagrant';"
$MYSQLCMD "GRANT ALL PRIVILEGES ON secretsanta.* TO vagrant@localhost;"

$MYSQLCMD "CREATE USER root@'192.168.33.1' IDENTIFIED BY 'vagrant';"
$MYSQLCMD "GRANT ALL PRIVILEGES ON *.* TO root@'192.168.33.1';"

$MYSQLCMD "FLUSH PRIVILEGES;"
