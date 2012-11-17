class apache2 {
    # package "php5" depends on "apache2-mpm-prefork"
    package { "apache2" :
        name => "php5",
        ensure => present,
    }

    service { "apache2" :
        ensure => running,
        require => Package["apache2"],
    }

    class { "php" :
        require => Package["apache2"],
    }

    # Change user
    exec { "UserChange" :
        command => "sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars",
        onlyif  => "grep -c 'APACHE_RUN_USER=www-data' /etc/apache2/envvars",
        require => Package["apache2"],
        notify  => Service["apache2"],
    }

    # Change group
    exec { "GroupChange" :
        command => "sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
        onlyif  => "grep -c 'APACHE_RUN_GROUP=www-data' /etc/apache2/envvars",
        require => Package["apache2"],
        notify  => Service["apache2"],
    }

    exec { "a2enmod rewrite" :
        require => Package["apache2"],
        notify  => Service["apache2"],
    }

    exec { "reload-apache2" :
        command => "/etc/init.d/apache2 restart",
        require => Service["apache2"],
    }
}
