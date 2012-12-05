Welcome to Secret Santa
=======================

Welcome to the repository for SecretSanta.

###Installation

This project uses Vagrant / Puppet. Install [Vagrant](http://downloads.vagrantup.com/) and [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads)
on your machine. First, clone this repository. Then, navigate to the SecretSanta root directory and run the following command:

    $ vagrant up

The first time, this will take some time. It has to download a Debian image, install a full LAMP stack and download Symfony's vendors.
If you are a windows user, make sure to run the following commands before cloning (we actually think this is a bug, see [#3](https://github.com/tvlooy/SecretSanta/issues/3)):

    $ git config --global core.autocrlf false
    $ git config --global core.safecrlf true

Add 33.33.33.10 www.secretsanta.dev to your etc/hosts file (or just browse to the IP). You can SSH into the machine with:

    $ vagrant ssh

To stop the machine use:

    $ vagrant halt

This just stops the VM. If you want to remove it use:

    $ vagrant destroy

Note that it is sometimes necessary to manually restart apache (so it picks up it's new configs). This is a bug see [#5](https://github.com/tvlooy/SecretSanta/issues/5):

    $ sudo /etc/init.d/apache2 restart

###Mailing

All mail that is sent on the system, from any address to any address is delivered to the vagrant user. You can read this user's mailbox with the webmail that is configured on http://33.33.33.10/roundcube (user: vagrant, pass: vagrant)

If you can't login to roundcube, you probably didn't send any mails yet (so the user's mail directory doesn't exist yet). This is listed as a bug, see [#8](https://github.com/tvlooy/SecretSanta/issues/8).
