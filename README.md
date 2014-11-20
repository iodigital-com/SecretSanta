# Welcome to Secret Santa

Welcome to the repository for SecretSanta. See
[LICENSE](https://github.com/Intracto/SecretSanta/blob/master/LICENSE)
for usage terms.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e845a60-cf8f-4e83-97d3-ecacb19cd091/big.png)]
(https://insight.sensiolabs.com/projects/5e845a60-cf8f-4e83-97d3-ecacb19cd091)

## Getting started

First get the code on your machine.

    git clone git@github.com:Intracto/SecretSanta.git
    cd SecretSanta

Install VirtualBox 4.3.10 and Vagrant 1.6.2 (or more recent).

    vagrant up

Add the records from ```shell_provisioner/config/hosts.txt``` in your own /etc/hosts file.

Browse to http://dev.secretsantaorganizer.com/app_dev.php to see the project homepage.

## Extra info

If you need root in the box, use ```sudo -i``` or password ```vagrant```.

All mails on the system are intercepted, regardless sender and receiver, and are delivered locally. You can access
these mails from the URL ```/roundcube```.

There is access to the MySQL database from URL ```/phpmyadmin```, or with a remote connection.
Login with user ```root```, password ```vagrant```.

Xdebug remote debugging is enabled. Configure your PhpStorm so you can step debug the code.

Run the tests with:

    phpunit.phar -c app

Note, don't worry if you see the shell provisioning print a lot of red lines. It all works fine.
