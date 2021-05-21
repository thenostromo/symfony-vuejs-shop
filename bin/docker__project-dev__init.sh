#!/usr/bin/env bash
docker-compose -f ./.docker/docker-compose.yml up --build -d

docker exec php-fpm chmod 755 bin/init-project__dev.sh
docker exec php-fpm /bin/sh -c "/var/www/bin/project-dev__init.sh;"

