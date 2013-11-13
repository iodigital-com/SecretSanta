set :user, "secretsantaplannercom"
set :group, "secretsantaplannercom"
server "ssh012.webhosting.be", :web, :app, :db, :primary => true
set :deploy_to, "/data/sites/web/secretsantaplannercom/www"

set :use_sudo, false
