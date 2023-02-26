.PHONY: install
install: ## install project
	cp -u -p .env .env.local
	symfony composer install
	symfony console doctrine:database:drop --force
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n


.PHONY: start
start: ## start project
	$(MAKE) install
	symfony serve -d

.PHONY: reset-db
reset-db: ## reset database
	symfony console doctrine:database:drop --force
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
