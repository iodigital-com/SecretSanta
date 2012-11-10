class secretsanta::setup {

    # Install some default packages
    $default_packages = [ "htop", "strace", "sysstat", "git" ]
    package { $default_packages :
        ensure => present,
    }

    exec { "dotdeb key" :
        command => "/usr/bin/wget -q -O - http://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -",
    }

    exec { "add dotdeb" :
        unless => "/bin/grep -c dotdeb /etc/apt/sources.list",
        command => "/bin/cat << EOF >> /etc/apt/sources.list
deb http://packages.dotdeb.org squeeze all
deb-src http://packages.dotdeb.org squeeze all
deb http://packages.dotdeb.org squeeze-php54 all
deb-src http://packages.dotdeb.org squeeze-php54 all
EOF",
        require => Exec["dotdeb key"],
    }

    exec { "dotdeb update" :
        command => '/usr/bin/apt-get update',
        require => Exec["add dotdeb"],
    }
}
