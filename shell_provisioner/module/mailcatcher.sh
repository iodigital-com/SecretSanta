#!/bin/bash

# Mailcatcher

# Remove Exim (default Debian)
apt-get remove -y exim4 exim4-base exim4-config exim4-daemon-light

# Mailcatcher
apt-get install -y libsqlite3-dev ruby-dev rubygems g++
gem install --no-ri --no-rdoc mailcatcher

# Supervisor
apt-get install -y supervisor

sed -i "s/# Required-Start:.*/# Required-Start:    \$all/" /etc/init.d/supervisor
sed -i "s/# Required-Stop:.*/# Required-Stop:/" /etc/init.d/supervisor
update-rc.d supervisor defaults

cat << EOF >/etc/supervisor/conf.d/mailcatcher.conf
[program:mailcatcher]
command=/usr/local/bin/mailcatcher -f
autostart=true
autorestart=true
stderr_logfile=/var/log/mailcatcher.err.log
stdout_logfile=/var/log/mailcatcher.out.log
EOF

service supervisor restart

# Install Postfix
echo "postfix postfix/mailname string ${APP_DOMAIN}" | debconf-set-selections
echo "postfix postfix/myhostname string ${APP_DOMAIN}" | debconf-set-selections
echo "postfix postfix/destinations string '${APP_DOMAIN}, localhost'" | debconf-set-selections
echo "postfix postfix/main_mailer_type string 'Internet Site'" | debconf-set-selections

apt-get -y install postfix postfix-pcre

cat << EOF >>/etc/postfix/main.cf

relayhost = 127.0.0.1:1025
EOF

service postfix restart

# Add Apache vhost
cat ${CONFIG_PATH}/apache/mailcatcher.${APP_DOMAIN}.conf > /etc/apache2/sites-available/mailcatcher.${APP_DOMAIN}.conf

a2ensite mailcatcher.${APP_DOMAIN}
service apache2 restart

# Install the mail command (for CLI debugging)
apt-get install -y mailutils
