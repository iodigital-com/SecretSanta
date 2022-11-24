.PHONY: all deploy deploy-debug db-backup
.RECIPEPREFIX = |

all:
| @echo "usage: make"
| @echo "  [deploy]        deploy to production"
| @echo "  [deploy-debug]  deploy to production, with debug output"

deploy:
| ssh secretsanta@secretsantaorganizer.com 'bash -s' < deploy.sh

deploy-debug:
| ssh secretsanta@secretsantaorganizer.com 'export DBG=1; bash -s' < deploy.sh

test: lint phpstan phpunit-nocoverage

lint:
| find src -type f -name '*.php' -print0 | xargs -0 -n1 -P4 php -l -n | (! grep -v "No syntax errors detected" )

phpunit-nocoverage:
| php ./vendor/bin/phpunit --no-coverage

phpstan:
| php -dmemory_limit=-1 ./vendor/bin/phpstan analyse --level=5 src
