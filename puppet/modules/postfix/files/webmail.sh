#!/bin/sh

# enable /roundcube URL
sed -i 's/#    Alias \/roundcube/Alias \/roundcube/' /etc/apache2/conf.d/roundcube

# connect to localhost by default + add the 'to' field
sed -i "s/'default_host'] = ''/'default_host'] = 'localhost'/" /etc/roundcube/main.inc.php 
sed -i "s/'from', 'date'/'from', 'to', 'date'/" /etc/roundcube/main.inc.php 

# webserver runs as vagrant instead of www-data
chgrp vagrant /etc/roundcube/main.inc.php 
chgrp vagrant /etc/roundcube/debian-db.php 

/etc/init.d/apache2 restart
