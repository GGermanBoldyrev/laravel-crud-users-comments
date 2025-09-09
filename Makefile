SAIL=./vendor/bin/sail

.PHONY: up
up:
	@$(SAIL) up -d

.PHONY: down
down:
	@$(SAIL) down

ARGS = $(filter-out $@,$(MAKECMDGOALS))

.PHONY: artisan
artisan:
	@$(SAIL) artisan $(ARGS)

.PHONY: migrate
migrate:
	@$(SAIL) artisan migrate

.PHONY: migrate-fresh
migrate-fresh:
	@$(SAIL) artisan migrate:fresh --seed

.PHONY: seed
seed:
	@$(SAIL) artisan db:seed $(ARGS)
