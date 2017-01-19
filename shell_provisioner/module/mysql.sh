#!/bin/bash

# Percona server (MySQL)

# Add repository
wget https://repo.percona.com/apt/percona-release_0.1-4.jessie_all.deb
dpkg -i percona-release_0.1-4.jessie_all.deb

apt-get update

# Install server and client
echo "percona-server-server-5.6 percona-server-server/root_password password vagrant" | debconf-set-selections
echo "percona-server-server-5.6 percona-server-server/root_password_again password vagrant" | debconf-set-selections
apt-get -y install percona-server-server-5.6 percona-server-client-5.6

# Configuration
sed -i "s/\[mysqld\]/[mysqld]\ninnodb_file_per_table = 1/" /etc/mysql/my.cnf
sed -i 's/bind-address.*/bind-address\t\t= 0.0.0.0/' /etc/mysql/my.cnf
service mysql restart

# Add database
MYSQLCMD="mysql -uroot -pvagrant -e"
$MYSQLCMD "CREATE DATABASE secretsanta DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"

$MYSQLCMD "CREATE USER secretsanta@localhost IDENTIFIED BY 'vagrant';"
$MYSQLCMD "GRANT ALL PRIVILEGES ON secretsanta.* TO secretsanta@localhost;"

$MYSQLCMD "CREATE USER root@'192.168.33.1' IDENTIFIED BY 'vagrant';"
$MYSQLCMD "GRANT ALL PRIVILEGES ON *.* TO root@'192.168.33.1';"

$MYSQLCMD "FLUSH PRIVILEGES;"

# Install Percona toolkit and enable functions
$MYSQLCMD "CREATE FUNCTION fnv1a_64 RETURNS INTEGER SONAME 'libfnv1a_udf.so'"
$MYSQLCMD "CREATE FUNCTION fnv_64 RETURNS INTEGER SONAME 'libfnv_udf.so'"
$MYSQLCMD "CREATE FUNCTION murmur_hash RETURNS INTEGER SONAME 'libmurmur_udf.so'"

# Install Percona toolkit
apt-get install -y percona-toolkit
