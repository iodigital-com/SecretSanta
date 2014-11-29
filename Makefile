all:
	@echo "usage: make"
	@echo "        [deploy]    update the PROD env"
	@echo "        [db-backup] backup DEV database"


deploy:
	ssh secretsanta@secretsantaorganizer.com 'bash -s' < deploy.sh


db-backup:
	vagrant ssh -c "mysqldump -uroot -pvagrant secretsanta | gzip > /vagrant/dumps/database.sql.gz"
