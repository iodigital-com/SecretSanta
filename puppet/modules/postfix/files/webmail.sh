#!/bin/sh

# enable /roundcube URL
sed -i 's/#    Alias \/roundcube/Alias \/roundcube/' /etc/apache2/conf.d/roundcube

# connect to localhost by default + add the 'to' field
sed -i "s/'default_host'] = ''/'default_host'] = 'localhost'/" /etc/roundcube/main.inc.php 
sed -i "s/'from', 'date'/'from', 'to', 'date'/" /etc/roundcube/main.inc.php 
sed -i "s/'show_images'] = 0/'show_images'] = 2/" /etc/roundcube/main.inc.php
sed -i "s/'create_default_folders'] = FALSE/'create_default_folders'] = TRUE/" /etc/roundcube/main.inc.php

# create empty mailbox
mkdir -p /home/vagrant/Maildir/cur
mkdir -p /home/vagrant/Maildir/new
mkdir -p /home/vagrant/Maildir/tmp

# webserver runs as vagrant instead of www-data
chgrp vagrant /etc/roundcube/main.inc.php 
chgrp vagrant /etc/roundcube/debian-db.php 

/etc/init.d/apache2 restart
