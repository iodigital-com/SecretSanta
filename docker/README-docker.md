Docker setup for SecretSanta (Work In Progress!)
============================
This is a docker-compose setup you can use to run the SecretSanta stack on docker instead of vagrant.

Keep in mind:
------
- xdebug untested
- mail not arriving at mailhog

Usage:  
The first time you need to build the php image, as this uses a custom dockerfile to build. This can take a few minutes, but only needs to be run once (unless the Dockerfile-php changes).
***
docker-compose build
***
    
After that, you can start the environment.    
***
docker-compose -d up
***

This will bring up the different containers. If needed, they are pulled from Docker Hub. 
The first time you bring up the environment, MySQL will initialize a new data directory. This is mapped through a docker volume to <project-root>/docker/mysql so that the database is preserved between runs.

Included containers:
- santa-web (httpd:2.4)
- santa-php (php:7.1-fpm-alpine)
- santa-db (mysql:5.7)

Now change your parameters.yml file to point to the correct endpoints:
- database
-- host: santa-db
-- user: secretsanta
-- database: secretsanta
- mailers
-- host: santa-mailhog
-- port: 1025

You will need to create the schema through app/console. You can do this throught the santa-php container. 
***
docker-compose exec santa-php php /usr/local/apache2/htdocs/app/console doctrine:schema:create
***

Done! You can now connect to the app on your docker machine on https.