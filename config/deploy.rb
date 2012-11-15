require 'railsless-deploy'

set :application, "SecretSanta"
set :repository,  "git://github.com/tvlooy/SecretSanta.git"
set :deploy_to, "/var/www/secretsanta.ctors.net/"
set :scm, :git
set :deploy_via, :rsync_with_remote_cache

set :keep_releases, 3
after "deploy", "deploy:cleanup"

server "secretsanta.ctors.net:9999", :web, :app, :db, :primary => true
#role :web, "your web-server here"                          # Your HTTP server, Apache/etc
#role :app, "your app-server here"                          # This may be the same as your `Web` server
#role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
#role :db,  "your slave db-server here"

# if you want to clean up old releases on each deploy uncomment this:
# after "deploy:restart", "deploy:cleanup"

# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
