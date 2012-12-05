# sudo gem install capistrano capistrano-ext railsless-deploy capistrano_rsync_with_remote_cache capifony

require 'railsless-deploy'

set :application, "SecretSanta"
set :repository,  "git://github.com/tvlooy/SecretSanta.git"
set :scm, :git
set :deploy_via, :rsync_with_remote_cache

set :keep_releases, 2
after "deploy", "deploy:cleanup"

task :staging do
    set :deploy_to, "/var/www/secretsanta/"
    set :user, "secretsanta"
    server "labs.intracto.local", :web, :app, :db, :primary => true

    before "deploy:setup", :stgOwnerships
    after "deploy", :stgConfigSymfony
end

task :production do
    set :deploy_to, "/site/www/"
    set :user, "secretsantaplannercom"
    server "ssh012.webhosting.be", :web, :app, :db, :primary => true

    set :use_sudo, false
end

namespace :stgOwnerships do
  task :default do
    run "chown -R secretsanta:www-data #{release_path}"
  end
end

namespace :stgConfigSymfony do
  task :default do
    run "cp -R #{shared_path}/vendor/ #{release_path}/htdocs/"
    run "cp #{shared_path}/parameters.yml #{release_path}/htdocs/app/config/"
    run "cd #{release_path}/htdocs/; #{shared_path}/composer.phar update"
    run "cd #{release_path}/htdocs/; app/console cache:clear -env=prod"
    run "chown -R secretsanta:www-data #{release_path}"
    run "chmod -R 777 #{release_path}/htdocs/app/cache/ #{release_path}/htdocs/app/logs/"
  end
end
