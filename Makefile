.PHONY: install
install: ## install project
	symfony composer install


.PHONY: start
start: ## start project
	$(MAKE) install
	symfony serve -d
