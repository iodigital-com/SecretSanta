Welcome to Secret Santa
=======================

Welcome to the repository for SecretSanta.

###Installation

This project uses Vagrant / Puppet. Install [Vagrant](http://downloads.vagrantup.com/) and [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads)
on your machine. First, clone this repository. Then, navigate to the SecretSanta root directory and run the following command:

    $ vagrant up

The first time, this will take some time. It has to download a Debian image, install a full LAMP stack and download Symfony's vendors.
If you are a windows user, make sure to run the following commands before cloning:

    $ git config --global core.autocrlf false
    $ git config --global core.safecrlf true

Add 33.33.33.10 www.secretsanta.dev to your etc/hosts file. You can SSH into the machine with:

    $ vagrant ssh

To stop the machine use:

    $ vagrant halt

This just stops the VM. If you want to remove it use:

    $ vagrant destroy

###Mailing

The mailserver of the box is not set up yet (with puppet). So, how to work with the mailing?
Obviously we don't want to deliver mail during development. We just want to see it in our mailqueue.

First SSH into your box (vagrant ssh), then become root (sudo -i). As root run the command:

    $ dpkg-reconfigure postfix

Choose "internet Site" and accept all the defaults. Then put the queue's on hold and restart postfix:

    $ postconf -e 'smtpd_sender_restrictions = static:HOLD'
    $ /etc/init.d/postfix restart

You can now try to mail with the application. To see the queue, run:

    $ postqueue -p

If you want to see the content of a mail use the command:

    $ postcat -q AC47A245C1

Where AC47A245C1 is the queue ID of your mail (without the ending ! character, which just means "on hold").
