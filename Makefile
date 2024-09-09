.PHONY: all deploy deploy-debug db-backup
.RECIPEPREFIX = |

all:
| @echo "usage: make"
| @echo "  [deploy]        deploy to production"
| @echo "  [deploy-debug]  deploy to production, with debug output"
| @echo "  [test]          run all tests and checks"
| @echo "  [lint-php]      run php lint check"
| @echo "  [lint-twig]     run twig lint check"
| @echo "  [lint-yaml]     run yaml lint check"
| @echo "  [phpstan]       run phpstan static analysis"
| @echo "  [sec-check]     run sensiolabs security checker"

deploy:
| ssh secretsanta@secretsantaorganizer.com 'bash -s' < deploy.sh

deploy-debug:
| ssh secretsanta@secretsantaorganizer.com 'export DBG=1; bash -s' < deploy.sh

test: lint-php lint-twig lint-yaml phpstan sec-check phpunit-nocoverage

lint-php:
| find src -type f -name '*.php' -print0 | xargs -0 -n1 -P4 php -l -n | (! grep -v "No syntax errors detected" )

lint-twig:
| ./bin/console lint:twig templates

lint-yaml:
| ./bin/console lint:yaml config

phpunit-nocoverage:
| php ./vendor/bin/phpunit --no-coverage

phpstan:
| php -dmemory_limit=-1 ./vendor/bin/phpstan analyse --level=5 src

sec-check:
| ./bin/local-php-security-checker_1.0.0_linux_amd64
