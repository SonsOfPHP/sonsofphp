COMPOSER     = composer
PHP          = php
PHP_CS_FIXER = tools/php-cs-fixer/vendor/bin/php-cs-fixer
PHPSTAN      = tools/phpstan/vendor/bin/phpstan
PHPUNIT      = tools/phpunit/vendor/bin/phpunit

COVERAGE_DIR = docs/coverage

.DEFAULT_GOAL = help
.PHONY        = help

##
## ███████╗ ██████╗ ███╗   ██╗███████╗     ██████╗ ███████╗    ██████╗ ██╗  ██╗██████╗
## ██╔════╝██╔═══██╗████╗  ██║██╔════╝    ██╔═══██╗██╔════╝    ██╔══██╗██║  ██║██╔══██╗
## ███████╗██║   ██║██╔██╗ ██║███████╗    ██║   ██║█████╗      ██████╔╝███████║██████╔╝
## ╚════██║██║   ██║██║╚██╗██║╚════██║    ██║   ██║██╔══╝      ██╔═══╝ ██╔══██║██╔═══╝
## ███████║╚██████╔╝██║ ╚████║███████║    ╚██████╔╝██║         ██║     ██║  ██║██║
## ╚══════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝     ╚═════╝ ╚═╝         ╚═╝     ╚═╝  ╚═╝╚═╝
##

help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: composer-install ## Install Dependencies

composer-install: composer.json # Install Dependencies via Composer
	$(COMPOSER) install --no-interaction --prefer-dist --optimize-autoloader
	$(COMPOSER) install --working-dir=tools/php-cs-fixer --no-interaction --prefer-dist --optimize-autoloader
	$(COMPOSER) install --working-dir=tools/phpunit --no-interaction --prefer-dist --optimize-autoloader
	$(COMPOSER) install --working-dir=tools/phpstan --no-interaction --prefer-dist --optimize-autoloader
	$(COMPOSER) install --working-dir=packages/bard --no-interaction --prefer-dist --optimize-autoloader

purge: # Purge vendor and lock files
	rm -rf vendor/ packages/*/vendor/ packages/*/composer.lock

test: ## Run Tests
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT) --order-by=defects

lint: lint-php ## Lint files

lint-php: # lint php files
	@! find packages/ -name "*.php" -not -path "packages/**/vendor/*" | xargs -I{} $(PHP) -l '{}' | grep -v "No syntax errors detected"

coverage: ## Build Code Coverage Report
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --coverage-html $(COVERAGE_DIR)

phpstan: ## Run phpstan
	$(PHP) $(PHPSTAN) analyse --no-progress --no-interaction packages/

php-cs-fixer: ## run php-cs-fixer
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix -vv --diff --allow-risky=no --config=.php-cs-fixer.dist.php

testdox: ## Run tests and output testdox
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT) --testdox
