SHELL := /bin/bash
NODE_MODULES = ./node_modules
VENDOR = ./vendor

pgsql-connect:
	psql -h 127.0.0.1 -d ranked_choice -U rc_admin -W

##
## Контроль качества кода
## ----------------------
check: eslint php-cs-fixer

phpunit:
	php ./vendor/bin/phpunit

eslint:
	$(NODE_MODULES)/.bin/eslint assets/js/ --ext .js,.vue --fix

php-cs-fixer:
	$(VENDOR)/bin/php-cs-fixer fix src --allow-risky=yes --dry-run --diff --verbose

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
