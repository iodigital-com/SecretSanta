class secretsanta {
    class { 'secretsanta::setup' : }
    class { 'secretsanta::sql' :
        require => Class["secretsanta::setup"],
    }
    class { 'secretsanta::web' :
        require => Class["secretsanta::sql"],
    }
    #class { 'secretsanta::symfony2' :
    #   require => Class["secretsanta::web"],
    #}
}
