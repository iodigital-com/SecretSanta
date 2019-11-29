.PHONY: all deploy deploy-debug db-backup
.RECIPEPREFIX = |

all:
| @echo "usage: make"
| @echo "  [deploy]        update the PROD env"
| @echo "  [deploy-debug]  update the PROD env, show debug output"
| @echo "  [db-backup]     backup DEV database"

deploy:
| ssh secretsanta@secretsantaorganizer.com 'bash -s' < deploy.sh

deploy-debug:
| ssh secretsanta@secretsantaorganizer.com 'export DBG=1; bash -s' < deploy.sh

db-backup:
| vagrant ssh -c "mysqldump -uroot -pvagrant secretsanta | gzip > /vagrant/dumps/database.sql.gz"
