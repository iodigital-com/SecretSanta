#!/bin/bash

# OpenSSL

cp /etc/ssl/openssl.cnf /tmp
echo '[ subject_alt_name ]' >> /tmp/openssl.cnf
echo 'subjectAltName = DNS:dev.secretsantaorganizer.com, DNS:phpmyadmin.dev.secretsantaorganizer.com, DNS:mails.dev.secretsantaorganizer.com' >> /tmp/openssl.cnf
openssl req -x509 -days 7300 -nodes -newkey rsa:2048 \
  -config /tmp/openssl.cnf \
  -extensions subject_alt_name \
  -keyout dev.secretsantaorganizer.com.key \
  -out dev.secretsantaorganizer.com.pem \
  -subj '/C=BE/ST=Antwerp/L=Herentals/O=Secret Santa/OU=Developmetn/CN=dev.secretsantaorganizer.com/emailAddress=domainmaster@dev.secretsantaorganizer.com'

mv dev.secretsantaorganizer.com.key /etc/ssl/private
mv dev.secretsantaorganizer.com.pem /etc/ssl/certs
