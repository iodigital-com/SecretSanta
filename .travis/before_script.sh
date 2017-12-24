#!/usr/bin/env bash

echo "Setup application"
bin/console doctrine:database:create --env=test_travis -vvv || exit $?
bin/console cache:warmup --env=test_travis --no-debug -vvv || exit $?
bin/console doctrine:schema:update --force --env=test_travis || exit $?

echo "Setting the web assets up"
bin/console assets:install --env=test_travis --no-debug -vvv || exit $?
