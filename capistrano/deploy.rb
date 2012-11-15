require 'railsless-deploy'

set :application, "SecretSanta"
set :repository,  "git://github.com/tvlooy/SecretSanta.git"
set :deploy_to, "/var/www/secretsanta.ctors.net/"
set :scm, :git
set :deploy_via, :rsync_with_remote_cache

set :keep_releases, 3
after "deploy", "deploy:cleanup"

server "secretsanta.ctors.net:9999", :web, :app, :db, :primary => true
