#!/usr/bin/env bash
composer install;
php bin/console doctrine:migrations:migrate -n;
php bin/console doctrine:schema:update --force;
npm install;
npm run build;

exec "$@"
