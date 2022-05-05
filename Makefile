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
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT)

lint: lint-php ## Lint files

lint-php: # lint php files
	@! find packages/ -name "*.php" -not -path "packages/**/vendor/*" | xargs -I{} $(PHP) -l '{}' | grep -v "No syntax errors detected"

coverage: ## Build Code Coverage Report
	XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --coverage-html $(COVERAGE_DIR)

phpstan: ## Run phpstan
	$(PHP) $(PHPSTAN) analyse packages/

php-cs-fixer: ## run php-cs-fixer
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix -vvv packages/

#-----------
remote-add: # Add git remotes for all components
	git remote -v | grep -w clock || git remote add clock git@github.com:SonsOfPHP/clock.git
	git remote -v | grep -w cqrs || git remote add cqrs git@github.com:SonsOfPHP/cqrs.git
	git remote -v | grep -w event-dispatcher || git remote add event-dispatcher git@github.com:SonsOfPHP/event-dispatcher.git
	git remote -v | grep -w event-sourcing || git remote add event-sourcing git@github.com:SonsOfPHP/event-sourcing.git
	git remote -v | grep -w feature-toggle || git remote add feature-toggle git@github.com:SonsOfPHP/feature-toggle.git
	git remote -v | grep -w json || git remote add json git@github.com:SonsOfPHP/json.git
	git remote -v | grep -w money || git remote add money git@github.com:SonsOfPHP/money.git
	git remote -v | grep -w version || git remote add version git@github.com:SonsOfPHP/version.git

subtree-push: # Push changes to all subtrees
	git checkout main
	git pull -p origin main
	git push origin main
	git subtree push --prefix=packages/clock clock main
	git subtree push --prefix=packages/cqrs cqrs main
	git subtree push --prefix=packages/event-dispatcher event-dispatcher main
	git subtree push --prefix=packages/event-sourcing event-sourcing main
	git subtree push --prefix=packages/feature-toggle feature-toggle main
	git subtree push --prefix=packages/json json main
	git subtree push --prefix=packages/money money main
	git subtree push --prefix=packages/version version main

copy-license: # copy the LICENSE file to all the libraries and projects
	cp LICENSE packages/clock/LICENSE
	cp LICENSE packages/cqrs/LICENSE
	cp LICENSE packages/event-dispatcher/LICENSE
	cp LICENSE packages/event-sourcing/LICENSE
	cp LICENSE packages/feature-toggle/LICENSE
	cp LICENSE packages/json/LICENSE
	cp LICENSE packages/money/LICENSE
	cp LICENSE packages/version/LICENSE

joshua-fucked-up:
	git subtree split --prefix packages/clock -b clock
	git push -f clock clock:main
	git branch -D clock
	git subtree split --prefix packages/cqrs -b cqrs
	git push -f cqrs cqrs:main
	git branch -D cqrs
	git subtree split --prefix packages/event-dispatcher -b event-dispatcher
	git push -f event-dispatcher event-dispatcher:main
	git branch -D event-dispatcher
	git subtree split --prefix packages/event-sourcing -b event-sourcing
	git push -f event-sourcing event-sourcing:main
	git branch -D event-sourcing
	git subtree split --prefix packages/feature-toggle -b feature-toggle
	git push -f feature-toggle feature-toggle:main
	git branch -D feature-toggle
	git subtree split --prefix packages/json -b json
	git push -f json json:main
	git branch -D json
	git subtree split --prefix packages/money -b money
	git push -f money money:main
	git branch -D money
	git subtree split --prefix packages/version -b version
	git push -f version version:main
	git branch -D version
