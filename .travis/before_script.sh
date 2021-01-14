#!/usr/bin/env bash

echo "Setup application"
bin/console doctrine:database:create --env=test -vvv || exit $?
bin/console cache:warmup --env=test --no-debug -vvv || exit $?
bin/console doctrine:schema:update --force --env=test || exit $?

echo "Setting the web assets up"
bin/console assets:install --env=test --no-debug -vvv || exit $?
