#!/usr/bin/env bash

cp "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/files/behat-travis.yml" ./behat.yml

# Configure display
/sbin/start-stop-daemon --start --quiet --pidfile /tmp/xvfb_99.pid --make-pidfile --background --exec /usr/bin/Xvfb -- :99 -ac -screen 0 1680x1050x16
export DISPLAY=:99

chromedriver_latest_version=$(curl -s https://chromedriver.storage.googleapis.com/LATEST_RELEASE)

# Download and configure ChromeDriver if it does not exist or is outdated
if [[ ! -f $BUILD_CACHE_DIR/chromedriver || $($BUILD_CACHE_DIR/chromedriver --version | grep -Po '[^a-zA-Z (\)]+(?![^\(]*\))') != "$chromedriver_latest_version" ]]; then
    curl https://chromedriver.storage.googleapis.com/${chromedriver_latest_version}/chromedriver_linux64.zip > chromedriver.zip
    unzip -o chromedriver.zip -d $BUILD_CACHE_DIR
    rm chromedriver.zip
fi

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

# Run Selenium with ChromeDriver
echo "Start selenium"
PATH=$PATH:$BUILD_CACHE_DIR vendor/bin/selenium-server-standalone > $TRAVIS_BUILD_DIR/selenium.log 2>&1 &

# Run phpunit tests (Temporary re-enable xdebug to generate coverage report)
phpenv config-add ~/xdebug.ini
vendor/bin/phpunit -c phpunit.xml.dist --coverage-text || exit $?
phpenv config-rm xdebug.ini

# Run webserver
$BUILD_CACHE_DIR/symfony server:ca:install --no-interaction
APP_ENV=test $BUILD_CACHE_DIR/symfony server:start -d --no-interaction

# Run behat tests
vendor/bin/behat --strict --stop-on-failure -f progress || exit $?

$BUILD_CACHE_DIR/symfony server:stop --no-interaction
