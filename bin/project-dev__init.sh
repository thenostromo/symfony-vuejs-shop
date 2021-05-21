#!/usr/bin/env bash
echo "Install dependencies";
composer install;

echo "Clear cache";
symfony console cache:clear;

echo "Update database";
symfony console doctrine:migrations:migrate --no-interaction;
symfony console doctrine:schema:update --force;

echo "Change permissions to cache";
#chmod -R 777 var/*

echo "Install node_modules";
#npm install;

echo "Build javascript for production";
#npm run build;

#echo "Run supervisor"
#service supervisor stop
#service supervisor start
#supervisorctl reread
#supervisorctl update
#supervisorctl start messenger-consume:*
symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async

exec "$@"
