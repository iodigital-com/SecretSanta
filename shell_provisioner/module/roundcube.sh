#!/bin/bash

# Remove Exim (default Debian)
apt-get remove -y exim4 exim4-base exim4-config exim4-daemon-light

# Install Postfix
echo "postfix postfix/mailname string ${POSTFIX_HOSTNAME}" | debconf-set-selections
echo "postfix postfix/myhostname string ${POSTFIX_HOSTNAME}" | debconf-set-selections
echo "postfix postfix/destinations string ${POSTFIX_HOSTNAME}, localhost'" | debconf-set-selections
echo "postfix postfix/main_mailer_type string 'Internet Site'" | debconf-set-selections

apt-get -y install postfix postfix-pcre

echo '/@.*/ vagrant@localhost' > /etc/postfix/virtual_forwardings.pcre
echo '/^.*/ OK' > /etc/postfix/virtual_domains.pcre

cat << EOF >>/etc/postfix/main.cf

virtual_alias_domains =
virtual_alias_maps = pcre:/etc/postfix/virtual_forwardings.pcre
virtual_mailbox_domains = pcre:/etc/postfix/virtual_domains.pcre
home_mailbox = Maildir/
EOF

sed -i 's/mailbox_command = .*/mailbox_command =/' /etc/postfix/main.cf
sed -i 's/myhostname = .*/myhostname =  "${POSTFIX_HOSTNAME}' /etc/postfix/main.cf

service postfix restart

# Install Roundcube and Dovecot
echo "roundcube-core roundcube/password-confirm password vagrant" | debconf-set-selections
echo "roundcube-core roundcube/mysql/admin-pass password vagrant" | debconf-set-selections
echo "roundcube-core roundcube/mysql/app-pass password vagrant" | debconf-set-selections
echo "roundcube-core roundcube/app-password-confirm password vagrant" | debconf-set-selections
echo "roundcube-core roundcube/dbconfig-install boolean true" | debconf-set-selections
echo "roundcube-core roundcube/database-type select mysql" | debconf-set-selections

apt-get -y install roundcube-core dovecot-imapd php-mdb2-driver-mysql

sed -i 's/mail_location = .*/mail_location = maildir:~\/Maildir/' /etc/dovecot/conf.d/10-mail.conf
/etc/init.d/dovecot restart

sed -i "s/#    Alias \/roundcube/Alias \/roundcube/" /etc/apache2/conf.d/roundcube
/etc/init.d/apache2 restart

chown -R vagrant:vagrant /etc/roundcube/
sed -i "s/'default_host'] = ''/'default_host'] = 'localhost'/" /etc/roundcube/main.inc.php
sed -i "s/'from', 'date'/'from', 'to', 'date'/" /etc/roundcube/main.inc.php
sed -i "s/'show_images'] = 0/'show_images'] = 2/" /etc/roundcube/main.inc.php
sed -i "s/'create_default_folders'] = FALSE/'create_default_folders'] = TRUE/" /etc/roundcube/main.inc.php
sed -i "s#=> 'rcmloginuser#=> 'rcmloginuser', 'value' => 'vagrant#" /var/lib/roundcube/program/include/rcube_template.php
sed -i "s#=> 'rcmloginpwd#=> 'rcmloginpwd', 'value' => 'vagrant#" /var/lib/roundcube/program/include/rcube_template.php
mkdir -p /home/vagrant/Maildir/{cur,new,tmp}
chown -R vagrant:vagrant /home/vagrant/Maildir

# Install the mail command (for CLI debugging)
apt-get install -y mailutils
