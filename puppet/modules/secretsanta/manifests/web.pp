class secretsanta::web {
    class { 'apache2' :
        require => Class["secretsanta::sql"],
    }

    $default_packages = [ "phpmyadmin", "php5-intl", "php5-apc" ]
    package { $default_packages :
        ensure => present,
        require => Package["apache2"],
    }

    # Enable PMA on /phpmyadmin
    exec { "include pma" :
        unless => "/bin/grep -c 'Include /etc/phpmyadmin/apache.conf' /etc/apache2/apache2.conf",
        command => "echo 'Include /etc/phpmyadmin/apache.conf' >> /etc/apache2/apache2.conf",
        require => Package["apache2"],
        notify  => Exec["reload-apache2"],
    }

    # Configure apache virtual host
    apache2::vhost { $params::host :
        documentroot => "/vagrant/Symfony2/web",
    }
}
