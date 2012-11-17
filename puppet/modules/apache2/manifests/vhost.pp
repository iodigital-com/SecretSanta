define apache2::vhost (
    $documentroot = "/vagrant",
    $aliases = ""
) {
    file { "/etc/apache2/sites-available/${name}.conf" :
        ensure => present,
        content => template("apache2/vhost.conf.erb"),
        require => Package['apache2'],
    }

    exec { "a2dissite default" :
        require => Package['apache2'],
    }

    exec { "a2ensite ${name}.conf" :
        require => File["/etc/apache2/sites-available/${name}.conf"],
        notify => Exec["reload-apache2"],
    }
}