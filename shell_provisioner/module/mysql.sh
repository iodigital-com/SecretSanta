#!/bin/bash

# MariaDB server (MySQL)

# Install server and client
echo "mariadb-server-10.3 mysql-server/root_password password vagrant" | debconf-set-selections
echo "mariadb-server-10.3 mysql-server/root_password_again password vagrant" | debconf-set-selections
apt-get -y install mariadb-server mariadb-client

# Configuration
sed -i "s/\[mysqld\]/[mysqld]\ninnodb_file_per_table = 1/" /etc/mysql/my.cnf
sed -i 's/bind-address.*/bind-address\t\t= 0.0.0.0/' /etc/mysql/my.cnf

service mysql restart

# Add database
MYSQLCMD="mysql -uroot -pvagrant -e"
$MYSQLCMD "CREATE DATABASE secretsanta DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

$MYSQLCMD "CREATE USER secretsanta@localhost IDENTIFIED BY 'vagrant';"
$MYSQLCMD "GRANT ALL PRIVILEGES ON \`secretsanta%\`.* TO secretsanta@localhost;"

$MYSQLCMD "CREATE USER root@'192.168.33.1' IDENTIFIED BY 'vagrant';"
$MYSQLCMD "GRANT ALL PRIVILEGES ON *.* TO root@'192.168.33.1';"

$MYSQLCMD "FLUSH PRIVILEGES;"

# Install postfix bounces table
mysql -uroot -pvagrant secretsanta < $CONFIG_PATH/postfix_bounce.sql

# Install Percona toolkit
apt-get install -y percona-toolkit

