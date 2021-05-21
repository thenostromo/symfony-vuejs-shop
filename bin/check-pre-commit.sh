#!/usr/bin/env bash
symfony check:security --dir=../
symfony check:requirements
php bin/console doctrine:schema:validate # check mapping
