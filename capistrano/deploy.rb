# sudo gem install capistrano capistrano-ext railsless-deploy capistrano_rsync_with_remote_cache

require 'railsless-deploy'

set :application, "SecretSanta"
set :repository,  "git://github.com/Intracto/SecretSanta.git"
set :scm, :git
set :deploy_via, :rsync_with_remote_cache

set :keep_releases, 2
after "deploy", "deploy:cleanup"

set :stages,        %w(production staging)
set :default_stage, "staging"
set :stage_dir,     "capistrano"
require 'capistrano/ext/multistage'

after "deploy:finalize_update", :install_symfony

namespace :install_symfony do
  task :default do
    run "cp -R #{shared_path}/vendor/ #{release_path}/htdocs/"
    run "cp #{shared_path}/parameters.yml #{release_path}/htdocs/app/config/"
    run "cp #{shared_path}/bootstrap.php.cache #{release_path}/htdocs/app/"

    run "mkdir #{release_path}/htdocs/app/cache/; mkdir #{release_path}/htdocs/app/logs/"
    run "cd #{release_path} && composer.phar update"
    run "cd #{release_path}/htdocs/; app/console assets:install web"

    #run "cd #{release_path}/htdocs/; app/console cache:clear -env=prod"
    run "chown -R #{user}:#{group} #{release_path}"
    run "chmod -R 777 #{release_path}/htdocs/app/cache/ #{release_path}/htdocs/app/logs/"
  end
end
