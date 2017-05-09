#!/bin/bash

# rsyslogd

echo "rsyslog-mysql rsyslog-mysql/dbconfig-install boolean true" | debconf-set-selections
apt-get -y install rsyslog-mysql

cat $CONFIG_PATH/rsyslogd/postfix.conf > /etc/rsyslog.d/postfix.conf
service rsyslog restart

