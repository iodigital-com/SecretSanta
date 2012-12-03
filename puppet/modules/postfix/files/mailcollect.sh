#!/bin/sh

/bin/cat << EOF >> /etc/postfix/main.cf
virtual_alias_domains =
virtual_alias_maps = pcre:/etc/postfix/virtual_forwardings.pcre
virtual_mailbox_domains = pcre:/etc/postfix/virtual_domains.pcre
home_mailbox = Maildir/
EOF

echo "/@.*/ vagrant" > /etc/postfix/virtual_forwardings.pcre
echo "/^.*/ OK" > /etc/postfix/virtual_domains.pcre

/etc/init.d/postfix restart
