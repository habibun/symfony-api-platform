SHELL := /bin/bash


.PHONY: init
init: ## create .env.local file from .env
	cp -u -p .env .env.local

.PHONY: up
up: ## docker start
	docker compose up -d --build

.PHONY: down
down: ## docker down
	docker compose down

.PHONY: install
install: ## install
	symfony composer install
	yarn install
	symfony console doctrine:database:drop --force --if-exists
	symfony console doctrine:database:create --if-not-exists
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:fixtures:load -n

.PHONY: reset-db
reset-db: ## reset database
	symfony console doctrine:database:drop --force --if-exists
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:fixtures:load -n

.PHONY: start
start: ## start
	symfony serve -d
	yarn encore dev --watch

.PHONY: log
log: ## log
	symfony server:log

.PHONY: reset-db-test
reset-db-test: ## reset test database
	symfony console doctrine:database:drop --force --env=test || true
	symfony console doctrine:database:create --env=test
	symfony console doctrine:migrations:migrate -n --env=test
	symfony console doctrine:fixtures:load -n --env=test
