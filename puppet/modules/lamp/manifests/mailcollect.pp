class lamp::mailcollect {
    class { 'postfix' :
        require => Class["lamp::web"],
    }
}
