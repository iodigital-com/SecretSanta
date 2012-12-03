class postfix {
    $postfix = [ "postfix", "postfix-pcre" ]
    package { $postfix :
        ensure => present,
    }

    service { "postfix" :
        ensure => running,
        require => Package["postfix"],
    }

    file { "mailcollect.sh" :
        path => "/tmp/mailcollect.sh",
        source => "puppet:///modules/postfix/mailcollect.sh",
        ensure => file,
        mode => 0644,
        owner => vagrant,
        group => vagrant,
        require => Package["postfix"],
    }
    exec { "configure mailcollect" :
        require => File["mailcollect.sh"],
        path => "/bin:/usr/bin",
        command => "sudo sh /tmp/mailcollect.sh",
        unless => "/usr/bin/file /etc/postfix/virtual_forwardings.pcre",
    }
}
