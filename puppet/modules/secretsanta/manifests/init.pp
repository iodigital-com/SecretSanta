class secretsanta {
    class { 'secretsanta::setup' : }
    class { 'secretsanta::sql' : }
    class { 'secretsanta::web' : }
    class { 'secretsanta::symfony2' : }
}
