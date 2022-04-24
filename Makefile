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

purge: # Purge vendor files
	rm -rf vendor/* src/SonsOfPHP/*/*/vendor/*

test: ## Run Tests
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT)

lint: lint-php ## Lint files

lint-php: # lint php files
	@! find src/ -name "*.php" -not -path "src/SonsOfPHP/**/vendor/*" | xargs -I{} $(PHP) -l '{}' | grep -v "No syntax errors detected"

coverage: ## Build Code Coverage Report
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --coverage-html $(COVERAGE_DIR)

phpstan: ## Run phpstan
	$(PHP) $(PHPSTAN) analyse src/

php-cs-fixer: ## run php-cs-fixer
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix src/

#-----------
remote-add: # Add git remotes for all components
	git remote -v | grep -w clock || git remote add clock git@github.com:SonsOfPHP/clock.git
	git remote -v | grep -w feature-toggle || git remote add feature-toggle git@github.com:SonsOfPHP/feature-toggle.git

subtree-push: # Push changes to all subtrees
	git subtree push --prefix=src/SonsOfPHP/Component/Clock clock main
	git subtree push --prefix=src/SonsOfPHP/Component/FeatureToggle feature-toggle main

copy-license: # copy the LICENSE file to all the libraries and projects
	cp LICENSE src/SonsOfPHP/Component/Clock/LICENSE
	cp LICENSE src/SonsOfPHP/Component/FeatureToggle/LICENSE
