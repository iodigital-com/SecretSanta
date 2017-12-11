#!/usr/bin/env bash

cp "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/files/behat-travis.yml" ./behat.yml

# Configure display
/sbin/start-stop-daemon --start --quiet --pidfile /tmp/xvfb_99.pid --make-pidfile --background --exec /usr/bin/Xvfb -- :99 -ac -screen 0 1680x1050x16
export DISPLAY=:99

chromium-browser --version

# Download and configure ChromeDriver
if [ ! -f $BUILD_CACHE_DIR/chromedriver ]; then
    # Using ChromeDriver 2.31 which supports Chrome 58-60, we're using Chromium 59
    curl http://chromedriver.storage.googleapis.com/2.31/chromedriver_linux64.zip > chromedriver.zip
    unzip chromedriver.zip
    mv chromedriver $BUILD_CACHE_DIR
fi

# this checks that the YAML config files contain no syntax errors
app/console lint:yaml app/config || exit $?
# this checks that the Twig template files contain no syntax errors
app/console lint:twig app/Resources/TwigBundle || exit $?
app/console lint:twig src/Intracto/SecretSantaBundle/Resources/views || exit $?
# this checks that the application doesn't use dependencies with known security vulnerabilities
app/console security:check --end-point=http://security.sensiolabs.org/check_lock || exit $?
# this checks that the composer.json and composer.lock files are valid
composer validate --strict || exit $?
# this checks that Doctrine's mapping configurations are valid
app/console doctrine:schema:validate --skip-sync -vvv --no-interaction || exit $?

# Run Selenium with ChromeDriver
echo "Start selenium"
PATH=$PATH:$BUILD_CACHE_DIR bin/selenium-server-standalone > $TRAVIS_BUILD_DIR/selenium.log 2>&1 &

# Run phpunit tests (Temporary re-enable xdebug to generate coverage report)
phpenv config-add ~/xdebug.ini
bin/phpunit -c app/phpunit.xml.dist --coverage-text || exit $?
phpenv config-rm xdebug.ini

# Run webserver
app/console server:run 127.0.0.1:8080 --env=test_travis --router=app/config/router_test_travis.php --no-debug --quiet > /dev/null 2>&1 &

# Run behat tests
bin/behat --strict -f progress || exit $?
