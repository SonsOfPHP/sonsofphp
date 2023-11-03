COMPOSER            = composer
MKDOCS              = mkdocs
PHP                 = php
PHP_CS_FIXER        = tools/php-cs-fixer/vendor/bin/php-cs-fixer
PHPUNIT             = tools/phpunit/vendor/bin/phpunit
PSALM               = tools/psalm/vendor/bin/psalm
PSALM_BASELINE_FILE = psalm-baseline.xml
BARD                = src/SonsOfPHP/Bard/bin/bard

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

install: composer-install tools-install ## Install Dependencies

upgrade: tools-upgrade

composer-install: composer.json # Install Dependencies via Composer
	XDEBUG_MODE=off $(COMPOSER) install --no-interaction --prefer-dist --optimize-autoloader
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=src/SonsOfPHP/Bard --no-interaction --prefer-dist --optimize-autoloader

purge: # Purge vendor and lock files
	rm -rf vendor/ src/SonsOfPHP/Bard/vendor/ src/SonsOfPHP/Bard/composer.lock
	rm -rf vendor/ src/SonsOfPHP/Bridge/*/vendor/ src/SonsOfPHP/Bridge/*/composer.lock
	rm -rf vendor/ src/SonsOfPHP/Bundle/*/vendor/ src/SonsOfPHP/Bundle/*/composer.lock
	rm -rf vendor/ src/SonsOfPHP/Component/*/vendor/ src/SonsOfPHP/Component/*/composer.lock

test: ## Run PHPUnit Tests
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT) --order-by=defects

phpunit: test

phpunit-install:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/phpunit --no-interaction --prefer-dist --optimize-autoloader

phpunit-upgrade:
	XDEBUG_MODE=off $(COMPOSER) upgrade --working-dir=tools/phpunit --no-interaction --prefer-dist --optimize-autoloader --with-all-dependencies

lint: lint-php ## Lint files

lint-php: # lint php files
	find src -name "*.php" -not -path "src/**/vendor/*" | xargs -I{} $(PHP) -l '{}'

coverage: ## Build Code Coverage Report
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --coverage-html $(COVERAGE_DIR)

coverage-clock:
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --testsuite clock --coverage-html $(COVERAGE_DIR)

coverage-event-dispatcher:
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --testsuite event-dispatcher --coverage-html $(COVERAGE_DIR)

coverage-event-sourcing:
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --testsuite event-sourcing --coverage-html $(COVERAGE_DIR)

coverage-http-factory:
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --testsuite http-factory --coverage-html $(COVERAGE_DIR)

psalm: ## Run psalm
	XDEBUG_MODE=off $(PHP) $(PSALM)

psalm-baseline: # Updates the baseline file
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PSALM) --update-baseline --set-baseline=$(PSALM_BASELINE_FILE)

psalm-github: # used with GitHub
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PSALM) --long-progress --monochrome --output-format=github --report=results.sarif

psalm-install:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/psalm --no-interaction --prefer-dist --optimize-autoloader

psalm-upgrade:
	XDEBUG_MODE=off $(COMPOSER) upgrade --working-dir=tools/psalm --no-interaction --prefer-dist --optimize-autoloader --with-all-dependencies

php-cs-fixer: ## run php-cs-fixer
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix -vv --diff --allow-risky=yes --config=.php-cs-fixer.dist.php

php-cs-fixer-install:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/php-cs-fixer --no-interaction --prefer-dist --optimize-autoloader

php-cs-fixer-upgrade:
	XDEBUG_MODE=off $(COMPOSER) upgrade --working-dir=tools/php-cs-fixer --no-interaction --prefer-dist --optimize-autoloader --with-all-dependencies

testdox: ## Run tests and output testdox
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT) --testdox

tools-install: psalm-install php-cs-fixer-install phpunit-install

tools-upgrade: psalm-upgrade php-cs-fixer-upgrade phpunit-upgrade

## Documentation
docs-install: ## Install deps for building docs
	pip install mkdocs
	pip install mkdocs-material

docs-upgrade: ## upgrade mkdocs
	pip install --upgrade mkdocs-material

docs-watch: ## Preview documentation locally
	$(MKDOCS) serve

docs-build: ## Build Site
	$(MKDOCS) build

## Package Management
packages-install: ## Runs `composer install` on each package
	$(BARD) install -n -vvv

packages-update: ## Runs `composer update` on each package
	$(BARD) update -n -vvv

packages-merge: ## Merges each package's composer.json into the root composer.json
	$(BARD) merge -n -vvv

packages-publish: ## Packages are published to their read-only repository
	$(BARD) publish -n -vvv

packages-release-patch: ## Release patch (0.0.x)
	$(BARD) release -n -vvv patch
