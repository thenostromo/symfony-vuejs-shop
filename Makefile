SHELL := /bin/bash

tests: export APP_ENV=test
tests:
	symfony console doctrine:database:drop --force || true
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:schema:update --force
	#symfony console doctrine:fixtures:load -n
	symfony console hautelook:fixtures:load -n
	symfony php ./vendor/bin/phpunit $@
.PHONY: tests
