# Welcome to Secret Santa Online gift exchange organizer!

[![Build Status](https://app.travis-ci.com/tvlooy/SecretSanta.svg?branch=master)](https://app.travis-ci.com/github/tvlooy/SecretSanta)

Secret Santa Organizer is a free online Secret Santa gift exchange organizer! Organize a Secret Santa party with friends,
family or even co-workers and add your wishlist.

See [LICENSE](https://github.com/iodigital-com/SecretSanta/blob/master/LICENSE) for usage terms.

## Getting started

First get the code on your machine.

```
git clone https://github.com/iodigital-com/SecretSanta.git
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
composer install
yarn
yarn build

cat << EOF >config/recaptcha_secrets.json
{
    "key": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "secret_key": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "action": "contact",
    "threshold": 0.5
}
EOF
```

Add these records to your own ```/etc/hosts``` file:

```
192.168.33.50 dev.secretsantaorganizer.com
192.168.33.50 mails.dev.secretsantaorganizer.com
192.168.33.50 phpmyadmin.dev.secretsantaorganizer.com
```

Browse to http://dev.secretsantaorganizer.com to see the project homepage.

## Extra info

If you need root in the box, use ```sudo -i``` or password ```vagrant```.

All mails on the system are intercepted, regardless sender and receiver, and are delivered locally. You can access
these mails from the URL ```mails.dev.secretsantaorganizer.com```.

There is access to the MySQL database from URL ```phpmyadmin.dev.secretsantaorganizer.com```, or with a remote connection.
Login with user ```secretsanta```, password ```vagrant```.

Xdebug remote debugging is enabled. Configure your PhpStorm so you can step debug the code.

Run the tests with:

```
composer phpunit
```

[Writing and running Behat tests is documented here.](https://github.com/iodigital-com/SecretSanta/blob/master/docs/behat.md)

## Documentation

See our [docs section](docs/README.md) for information about behat, assets, ...
