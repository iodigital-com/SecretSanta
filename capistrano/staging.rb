set :user, "secretsanta"
set :group, "www-data"
server "labs.intracto.local", :web, :app, :db, :primary => true
set :deploy_to, "/var/www/secretsanta/"
