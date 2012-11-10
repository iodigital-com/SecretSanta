define apache2::vhost (
    $documentroot = "/vagrant",
    $aliases = "",
    $timezone = "Europe/Brussels"
) {
    file { "/etc/apache2/sites-available/${name}.conf" :
        ensure => present,
        content => template("apache2/vhost.conf.erb"),
        require => Package['apache2'],
    }

    exec { "a2dissite default" : }

    exec { "a2ensite ${name}.conf" :
        require => File["/etc/apache2/sites-available/${name}.conf"],
        notify => Exec["reload-apache2"],
    }

    $php_changes = [
        'set PHP/error_reporting "E_ALL | E_STRICT"',
        'set PHP/display_errors On',
        'set PHP/display_startup_errors On',
        'set PHP/html_errors On',
        'set PHP/short_open_tag Off',
        "set Date/date.timezone ${timezone}",
    ]

    augeas { "php5-cli" :
        context => "/files/etc/php5/cli/php.ini",
        changes => $php_changes,
        require => Package["apache2"],
    }

    augeas { "php5-apache" :
        context => "/files/etc/php5/apache2/php.ini",
        changes => $php_changes,
        require => Package["apache2"],
        notify  => Exec["reload-apache2"],
    }

    exec { "reload-apache2" :
        command => "/etc/init.d/apache2 reload",
        refreshonly => true,
    }
}