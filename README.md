Welcome to Secret Santa
=======================

Welcome to the repository for SecretSanta. See
[LICENSE](https://github.com/Intracto/SecretSanta/blob/master/htdocs/src/Intracto/SecretSantaBundle/Resources/meta/LICENSE)
for usage terms.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e845a60-cf8f-4e83-97d3-ecacb19cd091/big.png)]
(https://insight.sensiolabs.com/projects/5e845a60-cf8f-4e83-97d3-ecacb19cd091)

Getting started
---------------

First get the code on your machine.

    git clone git@github.com:Intracto/SecretSanta.git
    cd SecretSanta/htdocs/
    php ../composer.phar install

The setup will ask you for some parameters. At least provide a `database_user` and `database_password` for a user that
is allowed to create databases. And choose a `database_name` that doesn't exist yet. Next set up the database:

    app/console doctrine:database:create
    app/console doctrine:schema:create

Install the assets:

    app/console assets:install web
    app/console assetic:dump -env=prod

Now you are ready to use the project:

    php -S localhost:8080 -t web

First browse to http://localhost:8080/config.php to see if you have your PHP environment set up correctly to run Symfony2.
Next, you can visit http://localhost:8080/app_dev.php to see the project homepage.

The tool sends out e-mail. By default it passes mail to localhost for delivery. It's up to you what you do with that.
You can configure a relay in app/config/parameters.yml if you want.

Run the tests with:

    cd htdocs
    phpunit -c app

