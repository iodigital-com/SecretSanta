#!/usr/bin/env bash

echo "Setup application"
app/console doctrine:database:create --env=test_travis -vvv || exit $?
app/console cache:warmup --env=test_travis --no-debug -vvv || exit $?
app/console doctrine:schema:update --force --env=test_travis || exit $?

echo "Setting the web assets up"
app/console assets:install --env=test_travis --no-debug -vvv || exit $?
