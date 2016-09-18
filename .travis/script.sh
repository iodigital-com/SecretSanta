#!/usr/bin/env bash

# Run phpunit tests
phpunit -c app/phpunit.xml.dist --coverage-text || exit $?
