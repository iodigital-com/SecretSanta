Docker setup for SecretSanta
============================
This is a docker-compose setup you can use to run the SecretSanta stack on docker instead of vagrant.

Keep in mind:
-------------
- no phpMyAdmin
- xdebug untested, feel free to do so and let us know ;)
- mails are only sent when using app_dev.php 

Getting started:
----------------
To start the docker environment, simply issue the following command.

```
> docker-compose -d up
```

This will bring up the different containers. The first time you bring up the environment, they are pulled from Docker Hub. The PHP container will need to be build due to the use of PHP modules (like PDO) that are not shipped with the default PHP container.
The MySQL container will also need to initialize a new data directory. This is mapped through a docker volume to <project-root>/docker/mysql so that the database is preserved between runs. All and all, this shouldn't take more than a few minutes on moderate hardware and a decent internet connection.

The next time you start your environment, it will use the already present container images/builds, and starting the environment will only take a matter of seconds. If the Dockerfile-php has changed (eg. to add additional modules), you will need to rebuild it though.

```
> docker-compose build
```

Included containers:
- santa-web (httpd:2.4)
- santa-php (php:7.2-fpm-alpine)
- santa-db (mysql:5.7)
- santa-mailhog (mailhog/mailhog)

Now change your parameters.yml file to point to the correct endpoints:
- database
-- host: santa-db
-- user: secretsanta
-- database: secretsanta
- mailers
-- host: santa-mailhog
-- port: 1025

You will need to create the schema through bin/console. You can do this throught the santa-php container. 

```
> docker-compose exec santa-php php /usr/local/apache2/htdocs/app/console doctrine:schema:create
```

Done! You can now connect to the app on your docker machine on one of the exposed ports. If you're running on Linux, or using the native Docker for Windows, this is probably just on your localhost. If you're running docker on Mac or Windows through Docker toolbox, the default IP address of the docker machine is 192.168.99.100.

Ports:
- 80, HTTP, santa-web
- 443, HTTPS, santa-web
- 8025, HTTP, santa-mailhog

Internally the containers are able to connect on other ports as well (eg. MySQL). If you need to connect to these ports directly from your machine, you need to add the ports to the ports section of the container in the docker-compse.yml file and restart your environment.

If for any reason you want to shell into a running container, you can use docker-exec to do so. Most containers have either /bin/sh or /bin/bash available.

```
> docker-compose exec -ti santa-php /bin/sh
```
