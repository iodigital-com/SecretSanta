# sudo gem install capistrano capistrano-ext railsless-deploy capistrano_rsync_with_remote_cache capifony

require 'railsless-deploy'

set :application, "SecretSanta"
set :repository,  "git://github.com/tvlooy/SecretSanta.git"
set :deploy_to, "/var/www/secretsanta/"
set :scm, :git
set :deploy_via, :rsync_with_remote_cache

set :keep_releases, 2
after "deploy", "deploy:cleanup"

set :user, "secretsanta"
set :use_sudo, false
server "192.168.1.216", :web, :app, :db, :primary => true # labs server

# Usage (yes, this is todo):
# 1) chown -R secretsanta:www-data * 
# 2) cap deploy
# 3) cp -R shared/vendor/ current/htdocs/
#    cp shared/parameters.yml current/htdocs/app/config/
#    cd current/htdocs/
#    /var/www/secretsanta/shared/composer.phar update
#    app/console cache:clear -env=prod
#    chown -R secretsanta:www-data *
#    chmod -R 777 app/cache/ app/logs/
