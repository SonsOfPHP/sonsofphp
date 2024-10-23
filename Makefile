# start: Executables
DOCKER_COMPOSE = docker compose
COMPOSER       = composer
PHP            = php
# end: Executables

# start: Tools
BARD         = src/SonsOfPHP/Bard/bin/bard
BARD_COMPILE = src/SonsOfPHP/Bard/bin/compile
CHURN        = tools/churn/vendor/bin/churn
INFECTION    = tools/infection/vendor/bin/infection
PHP_CS_FIXER = tools/php-cs-fixer/vendor/bin/php-cs-fixer
PHPACTOR     = tools/phpactor/vendor/bin/phpactor
PHPUNIT      = tools/phpunit/vendor/bin/phpunit
PSALM        = tools/psalm/vendor/bin/psalm
RECTOR       = tools/rector/vendor/bin/rector
# end: Tools

# start: Config Files
CHURN_CONFIG        = churn.yml
INFECTION_CONFIG    = infection.json5
PHP_CS_FIXER_CONFIG = .php-cs-fixer.dist.php
PHPUNIT_CONFIG      = phpunit.xml.dist
PSALM_CONFIG        = psalm.xml
RECTOR_CONFIG       = rector.php
# end: Config Files

# start: Config Options
COMPOSER_INSTALL_OPTIONS ?= --no-interaction --prefer-dist --optimize-autoloader
COMPOSER_UPDATE_OPTIONS  ?= --no-interaction --prefer-dist --optimize-autoloader --with-all-dependencies
# end: Config Options

PSALM_BASELINE_FILE = build/psalm-baseline.xml
COVERAGE_DIR = docs/coverage

XDEBUG_MODE ?= off
PHPUNIT_TESTSUITE ?= all
PHPUNIT_OPTIONS ?=

.DEFAULT_GOAL = help

##
## ███████╗ ██████╗ ███╗   ██╗███████╗     ██████╗ ███████╗    ██████╗ ██╗  ██╗██████╗
## ██╔════╝██╔═══██╗████╗  ██║██╔════╝    ██╔═══██╗██╔════╝    ██╔══██╗██║  ██║██╔══██╗
## ███████╗██║   ██║██╔██╗ ██║███████╗    ██║   ██║█████╗      ██████╔╝███████║██████╔╝
## ╚════██║██║   ██║██║╚██╗██║╚════██║    ██║   ██║██╔══╝      ██╔═══╝ ██╔══██║██╔═══╝
## ███████║╚██████╔╝██║ ╚████║███████║    ╚██████╔╝██║         ██║     ██║  ██║██║
## ╚══════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝     ╚═════╝ ╚═╝         ╚═╝     ╚═╝  ╚═╝╚═╝
##
##=====================================================================================
##

.PHONY: help
help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: install
install: composer.lock $(BARD) $(CHURN) $(INFECTION) $(PHP_CS_FIXER) $(PHPACTOR) $(PHPUNIT) $(PSALM) $(RECTOR) ## Install Dependencies
	@mkdir -p build/{cache,logs,config}
	@$(COMPOSER) githooks

.PHONY: update
update: ## Update all the dependencies (root, tools, and packages)
	$(info COMPOSER_UPDATE_OPTIONS : $(COMPOSER_UPDATE_OPTIONS))
	XDEBUG_MODE=off $(COMPOSER) update $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/churn $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/infection $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/php-cs-fixer $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/phpactor $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/phpunit $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/psalm $(COMPOSER_UPDATE_OPTIONS)
	XDEBUG_MODE=off $(COMPOSER) update --working-dir=tools/rector $(COMPOSER_UPDATE_OPTIONS)
	@$(MAKE) pkg-update
	@$(COMPOSER) githooks

.PHONY: clean
clean: ## Remove all vendor folders, composer.lock files, and removes build artifacts
	rm -rf build/{cache,logs}/*
	rm -rf vendor/ composer.lock
	rm -rf src/SonsOfPHP/Bard/vendor/ src/SonsOfPHP/Bard/composer.lock
	rm -rf src/SonsOfPHP/Bridge/*/vendor/ src/SonsOfPHP/Bridge/*/composer.lock
	rm -rf src/SonsOfPHP/Bridge/*/*/vendor/ src/SonsOfPHP/Bridge/*/*/composer.lock
	rm -rf src/SonsOfPHP/Bridge/*/*/*/vendor/ src/SonsOfPHP/Bridge/*/*/*/composer.lock
	rm -rf src/SonsOfPHP/Bundle/*/vendor/ src/SonsOfPHP/Bundle/*/composer.lock
	rm -rf src/SonsOfPHP/Component/*/vendor/ src/SonsOfPHP/Component/*/composer.lock
	rm -rf src/SonsOfPHP/Contract/*/vendor/ src/SonsOfPHP/Contract/*/composer.lock
	rm -rf src/tools/*/vendor/ src/tools/*/composer.lock

# This will upgrade the code to whatever the standards are
# NOTE: This may make changes to the source code
.PHONY: upgrade-code
upgrade-code: $(RECTOR) $(PHP_CS_FIXER)
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix -vv --diff --allow-risky=yes --config=$(PHP_CS_FIXER_CONFIG)
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(RECTOR) --config=$(RECTOR_CONFIG)

# NOTE: This may make changes to the source code
.PHONY: fix-code
fix-code: PSALM_ISSUES=all
fix-code: upgrade-code $(PSALM)
	XDEBUG_MODE=off $(PHP) $(PSALM) --alter --issues=$(PSALM_ISSUES) --dry-run

##---- Testing ------------------------------------------------------------------------
.PHONY: test
test: $(PHPUNIT) ## Run PHPUnit Tests
	XDEBUG_MODE=$(XDEBUG_MODE) \
	$(PHP) \
	-dxdebug.mode=$(XDEBUG_MODE) \
	-dapc.enable_cli=1 \
	$(PHPUNIT) \
	--cache-result \
	--testsuite=$(PHPUNIT_TESTSUITE) \
	$(PHPUNIT_OPTIONS)

.PHONY: coverage
coverage: XDEBUG_MODE=coverage
coverage: PHPUNIT_OPTIONS=--coverage-html $(COVERAGE_DIR)
coverage: test ## Build Code Coverage Report

##---- Docker -------------------------------------------------------------------------
.PHONY: docker-up
docker-up: ## Start containers
	@$(DOCKER_COMPOSE) up --detach --remove-orphans

.PHONY: docker-down
docker-down: ## Shutdown containers
	@$(DOCKER_COMPOSE) down --remove-orphans

.PHONY: docker-logs
docker-logs: ## Show live logs
	@$(DOCKER_COMPOSE) logs --tail=0 --follow

##---- Code Quality -------------------------------------------------------------------
.PHONY: lint
lint: ## Lint PHP files
	find src -name "*.php" -not -path "src/**/vendor/*" | xargs -I{} $(PHP) -l '{}'

.PHONY: php-cs-fixer
php-cs-fixer: $(PHP_CS_FIXER) ## Run php-cs-fixer (dry-run)
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix -vv --diff --allow-risky=yes --config=$(PHP_CS_FIXER_CONFIG) --dry-run

.PHONY: psalm
psalm: $(PSALM) ## Run Psalm
	XDEBUG_MODE=off $(PHP) $(PSALM) --show-info=true --config=$(PSALM_CONFIG)

.PHONY: psalm-baseline
psalm-baseline: $(PSALM) # Updates the baseline file
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PSALM) --update-baseline --set-baseline=$(PSALM_BASELINE_FILE)

.PHONY: psalm-github
psalm-github: $(PSALM) # used with GitHub
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PSALM) --long-progress --monochrome --output-format=github --report=results.sarif

.PHONY: infection
infection: $(INFECTION) ## Run Infection
	XDEBUG_MODE=develop \
	$(PHP) \
	-dxdebug.mode=develop \
	-dapc.enable_cli=1 \
	$(INFECTION) --debug -vvv --show-mutations

.PHONY: churn
churn: $(CHURN) ## Run Churn PHP
	$(CHURN)

.PHONY: rector
rector: $(RECTOR) ## Run Rector (dry-run)
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(RECTOR) --dry-run --config=$(RECTOR_CONFIG)

##---- Package Management -------------------------------------------------------------
.PHONY: pkg-install
pkg-install: $(BARD) ## Runs `composer install` on each package
	$(BARD) install -n -vvv

.PHONY: pkg-update
pkg-update: $(BARD) ## Runs `composer update` on each package
	$(info Bard: composer update)
	$(BARD) update -n -vvv

.PHONY: pkg-merge
pkg-merge: $(BARD) ## Merges each package's composer.json into the root composer.json
	$(BARD) merge -n -vvv

.PHONY: pkg-release-patch
pkg-release-patch: $(BARD) ## Release patch (0.0.x)
	$(BARD) release -n -vvv patch

#===============================================================================
$(BARD): src/SonsOfPHP/Bard/composer.lock

src/SonsOfPHP/Bard/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=src/SonsOfPHP/Bard $(COMPOSER_INSTALL_OPTIONS)

$(CHURN): tools/churn/composer.lock

tools/churn/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/churn $(COMPOSER_INSTALL_OPTIONS)

$(INFECTION): tools/infection/composer.lock

tools/infection/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/infection $(COMPOSER_INSTALL_OPTIONS)

$(PHP_CS_FIXER): tools/php-cs-fixer/composer.lock

tools/php-cs-fixer/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/php-cs-fixer $(COMPOSER_INSTALL_OPTIONS)

$(PHPACTOR): tools/phpactor/composer.lock

tools/phpactor/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/phpactor $(COMPOSER_INSTALL_OPTIONS)

$(PHPUNIT): tools/phpunit/composer.lock

tools/phpunit/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/phpunit $(COMPOSER_INSTALL_OPTIONS)

$(PSALM): tools/psalm/composer.lock

tools/psalm/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/psalm $(COMPOSER_INSTALL_OPTIONS)

$(RECTOR): tools/rector/composer.lock

tools/rector/composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install --working-dir=tools/rector $(COMPOSER_INSTALL_OPTIONS)

composer.lock:
	XDEBUG_MODE=off $(COMPOSER) install $(COMPOSER_INSTALL_OPTIONS)
