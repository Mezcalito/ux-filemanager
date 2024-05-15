# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT  = $(DOCKER_COMP) exec -w /srv/app php
NODE_CONT = $(DOCKER_COMP) run --rm -w /srv/app node

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
YARN     = $(NODE_CONT) yarn

.DEFAULT_GOAL= help

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make \033[36m<target>\033[0m\n"} /^[a-z\/\.A-Z0-9_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

## —— Docker —————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

php: ## Connect to the PHP container
	@$(PHP_CONT) sh

node: ## Connect to the Node container
	@$(NODE_CONT) sh

## —— Assets —————————————————————————————————————————————————————————————————
assets/watch:
	@$(YARN) watch

assets/build:
	@$(YARN) build

## —— CI —————————————————————————————————————————————————————————————————————
ci: ## Run all CI jobs
	@$(MAKE) static
	@$(MAKE) rector
	@$(MAKE) test

static: ## Run static analysis tools
	@$(PHP) -d memory_limit=-1 vendor/bin/phpstan analyse
	@$(PHP) -d memory_limit=-1 vendor/bin/php-cs-fixer fix

rector: ## Run rector
	@$(PHP) -d memory_limit=-1 vendor/bin/rector

test: ## Run tests
	@$(PHP) vendor/bin/phpunit