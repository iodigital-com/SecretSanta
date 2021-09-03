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

