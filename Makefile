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

purge: # Purge vendor and lock files
	rm -rf vendor/* src/SonsOfPHP/*/*/vendor/* src/SonsOfPHP/*/*/composer.lock

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
	git remote -v | grep -w event-dispatcher || git remote add event-dispatcher git@github.com:SonsOfPHP/event-dispatcher.git
	git remote -v | grep -w event-sourcing || git remote add event-sourcing git@github.com:SonsOfPHP/event-sourcing.git
	git remote -v | grep -w feature-toggle || git remote add feature-toggle git@github.com:SonsOfPHP/feature-toggle.git
	git remote -v | grep -w json || git remote add json git@github.com:SonsOfPHP/json.git
	git remote -v | grep -w money || git remote add money git@github.com:SonsOfPHP/money.git

subtree-push: # Push changes to all subtrees
	git checkout main
	git pull -p origin main
	git push origin main
	git subtree push --prefix=packages/component/clock clock main
	git subtree push --prefix=packages/component/event-dispatcher event-dispatcher main
	git subtree push --prefix=packages/component/event-sourcing event-sourcing main
	git subtree push --prefix=packages/component/feature-toggle feature-toggle main
	git subtree push --prefix=packages/component/json json main
	git subtree push --prefix=packages/component/money money main

copy-license: # copy the LICENSE file to all the libraries and projects
	cp LICENSE packages/component/clock/LICENSE
	cp LICENSE packages/component/event-dispatcher/LICENSE
	cp LICENSE packages/component/event-sourcing/LICENSE
	cp LICENSE packages/component/feature-toggle/LICENSE
	cp LICENSE packages/component/json/LICENSE
	cp LICENSE packages/component/money/LICENSE
