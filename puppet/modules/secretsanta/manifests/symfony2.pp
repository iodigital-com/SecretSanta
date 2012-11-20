class secretsanta::symfony2 {
    exec { "composer install" :
        cwd => "/usr/local/bin",
        command => "curl -s https://getcomposer.org/installer | php",
        require => Class["secretsanta::web"],
    }

    # Install the vendors
    exec { "vendorupdate" :
        command => "/usr/local/bin/composer.phar install",
        cwd     => "/vagrant/htdocs",
        require => Exec["composer install"],
        timeout => 0,
        tries   => 10,
    }

    # Create our initial db
    exec { "init_db" :
        command => "php app/console doctrine:schema:create || true",
        cwd     => "/vagrant/htdocs",
        require => Exec["vendorupdate"],
    }
}
