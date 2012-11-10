class secretsanta::web {
    class { 'apache2' :
        require => Class["secretsanta::sql"],
    }

    $default_packages = [ "phpmyadmin", "php5-intl", "php5-apc" ]
    package { $default_packages :
        ensure => present,
        require => Package["apache2"],
    }

    # Configure apache virtual host
    apache2::vhost { $params::host :
        documentroot => "/vagrant/Symfony2/web",
    }
}
