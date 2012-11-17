class php {
    $timezone = "Europe/Brussels"

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
}