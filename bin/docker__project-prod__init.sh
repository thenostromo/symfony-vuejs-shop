#!/usr/bin/env bash
docker-compose \
  -f ./.docker/docker-compose.yml \
  --env-file ./.docker/prod.env \
  up --build -d

docker exec php-fpm chmod 755 bin/init-project__prod.sh
docker exec php-fpm /bin/sh -c "/var/www/bin/project-prod__init.sh;"
