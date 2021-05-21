#!/usr/bin/env bash
echo "Install dependencies";
composer install --no-interaction --no-dev --optimize-autoloader;

echo "Clear cache";
php bin/console cache:clear --no-warmup;
php bin/console cache:warmup;

echo "Update database";
php bin/console doctrine:cache:clear-metadata;
php bin/console doctrine:migrations:migrate --no-interaction;
php bin/console doctrine:schema:update --force;

echo "Change permissions to cache";
chmod -R 777 var/*

echo "Install node_modules";
#npm install;

echo "Build javascript for production";
#npm run build;

echo "Run supervisor"
service supervisor stop
service supervisor start
#supervisorctl reread
#supervisorctl update
#supervisorctl start messenger-consume:*

exec "$@"
