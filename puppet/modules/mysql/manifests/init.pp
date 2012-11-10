class mysql {
    package { "mysql-server" :
        ensure => present,
    }

    service { "mysql" :
        ensure => running,
        require => Package["mysql-server"],
    }
}
