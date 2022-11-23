#!/usr/bin/env bash

# this checks that the YAML config files contain no syntax errors
bin/console lint:yaml config || exit $?
# this checks that the Twig template files contain no syntax errors
bin/console lint:twig templates || exit $?
# check with phpstan static analyzer
./vendor/bin/phpstan analyse || exit $?
# this checks that the application doesn't use dependencies with known security vulnerabilities
$BUILD_CACHE_DIR/symfony security:check || exit $?
# this checks that the composer.json and composer.lock files are valid
composer validate --strict || exit $?
# this checks that Doctrine's mapping configurations are valid
bin/console doctrine:schema:validate --env=test --skip-sync -vvv --no-interaction || exit $?

# Run phpunit tests (Temporary re-enable xdebug to generate coverage report)
phpenv config-add ~/xdebug.ini
vendor/bin/phpunit -c phpunit.xml.dist --coverage-text || exit $?
phpenv config-rm xdebug.ini
