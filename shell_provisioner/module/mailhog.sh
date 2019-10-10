#!/bin/bash

# Postfix
echo "postfix postfix/mailname string ${APP_DOMAIN}" | debconf-set-selections
echo "postfix postfix/myhostname string ${APP_DOMAIN}" | debconf-set-selections
echo "postfix postfix/destinations string '${APP_DOMAIN}, localhost'" | debconf-set-selections
echo "postfix postfix/main_mailer_type string 'Internet Site'" | debconf-set-selections

apt-get install -y postfix postfix-pcre

cat << EOF >>/etc/postfix/main.cf

relayhost = 127.0.0.1:1025
EOF

service postfix restart

# MailHog

wget -qO /usr/local/bin/mailhog https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64
chmod +x /usr/local/bin/mailhog

cat << EOF >/lib/systemd/system/mailhog.service
[Unit]
Description=Mailhog SMTP

[Service]
User=mailhog
Group=mailhog
WorkingDirectory=/home/mailhog
Restart=always
ExecStart=/usr/local/bin/mailhog -api-bind-addr 127.0.0.1:8025 -ui-bind-addr 127.0.0.1:8025 -smtp-bind-addr 127.0.0.1:1025

[Install]
WantedBy=multi-user.target
EOF

# don't run stuff as root
useradd mailhog -s /bin/false -m

systemctl enable mailhog
systemctl start mailhog

# Add Apache vhost
a2enmod proxy_wstunnel
cat ${CONFIG_PATH}/apache/mails.${APP_DOMAIN}.conf > /etc/apache2/sites-available/mails.${APP_DOMAIN}.conf
a2ensite mails.${APP_DOMAIN}
service apache2 restart

# Install the mail command (for CLI debugging)
apt-get install -y mailutils
