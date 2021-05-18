SHELL := /bin/bash
NODE_MODULES = ./node_modules
VENDOR = ./vendor

SYMFONY_COMPOSER = symfony composer
PORT = 8000

pgsql-connect:
	psql -h 127.0.0.1 -d ranked_choice -U rc_admin -W

cert-install:
	symfony server:ca:install

install: composer.lock
	${SYMFONY_COMPOSER} install --no-progress --prefer-dist --optimize-autoloader

serve: ## make run PORT="8000"
	symfony serve --daemon --port=$(PORT)

serve-stop:
	symfony server:stop

deploy: ## Full no-downtime deployment with EasyDeploy (with pre-deploy Git hooks)
	test -z "`git status --porcelain`"                 # Prevent deploy if there are modified or added files
	test -z "`git diff --stat --cached origin/master`" # Prevent deploy if there is something to push on master

dev: ## Rebuild assets for the dev env
	$(YARN) install --check-files
	$(YARN) run encore dev

watch: ## Watch files and build assets when needed for the dev env
	$(YARN) run encore dev --watch

build: ## Build assets for production
	$(YARN) run encore production

lint-js: ## Lints JS coding standarts
	$(NPX) eslint assets/js

fix-js: ## Fixes JS files
	$(NPX) eslint assets/js --fix


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
	symfony php ./vendor/bin/phpunit --group functional,integration,unit $@
.PHONY: tests
