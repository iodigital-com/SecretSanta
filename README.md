# Welcome to Secret Santa Online gift exchange organizer!

[![Build Status](https://travis-ci.org/Intracto/SecretSanta.svg?branch=master)](https://travis-ci.org/Intracto/SecretSanta)

Secret Santa Organizer is a free online Secret Santa gift exchange organizer! Organize a Secret Santa party with friends,
family or even co-workers and add your wishlist.

See [LICENSE](https://github.com/Intracto/SecretSanta/blob/master/LICENSE) for usage terms.

## Getting started

First get the code on your machine.

```
git clone git@github.com:Intracto/SecretSanta.git
cd SecretSanta
```

Install VirtualBox 6.0.12 and Vagrant 2.2.5 (or more recent).

```
vagrant up
```

Prepare site:

```
vagrant ssh
cd /vagrant
composer.phar install
yarn
yarn build

cat << EOF >app/config/recaptcha_secrets.json
{
    "key": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "secret_key": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "action": "contact",
    "threshold": 0.5
}
EOF
```

Download the GeoIP DB at (https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz)[https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz]
and put this file in ```/usr/local/share/GeoIP/GeoLite2-City.mmdb```.

Add these records to your own ```/etc/hosts``` file:

```
192.168.33.50 dev.secretsantaorganizer.com
192.168.33.50 mails.dev.secretsantaorganizer.com
192.168.33.50 phpmyadmin.dev.secretsantaorganizer.com
```

Browse to http://dev.secretsantaorganizer.com/app_dev.php to see the project homepage.

## Extra info

If you need root in the box, use ```sudo -i``` or password ```vagrant```.

All mails on the system are intercepted, regardless sender and receiver, and are delivered locally. You can access
these mails from the URL ```mails.dev.secretsantaorganizer.com```.

There is access to the MySQL database from URL ```phpmyadmin.dev.secretsantaorganizer.com```, or with a remote connection.
Login with user ```secretsanta```, password ```vagrant```.

Xdebug remote debugging is enabled. Configure your PhpStorm so you can step debug the code.

Run the tests with:

```
./bin/phpunit
```

[Writing and running Behat tests is documented here.](https://github.com/Intracto/SecretSanta/blob/master/docs/behat.md)

## Documentation

See our [docs section](docs/README.md) for information about behat, assets, ...
