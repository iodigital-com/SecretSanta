#!/bin/bash

# rsyslogd

cat $CONFIG_PATH/rsyslogd/postfix.conf > /etc/rsyslog.d/postfix.conf
service rsyslog restart
