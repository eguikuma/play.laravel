.PHONY: help install rebuild up down destroy restart ps test format lint ci ide-apply app-shell db-shell tinker route cache-clear insight db-migrate db-seed db-fresh benchmark

# Default target is help
.DEFAULT_GOAL := help

## @description Install the project
## @usage make install
install:
	-cp -n .env.example .env
	docker compose build --no-cache
	docker compose run --rm app composer install
	@make up
	@make cache-clear
	docker compose exec app php artisan key:generate
	@make db-migrate
	@make db-seed
	@make ide-apply
	@make restart
	@echo "╔═════════════════════════════════════════════════════════════╗"
	@echo "║                                                             ║"
	@echo "║                      Setup Complete!                        ║"
	@echo "║                                                             ║"
	@echo "║                Project is now ready to use                  ║"
	@echo "║                                                             ║"
	@echo "║                Please open http://localhost                 ║"
	@echo "║                                                             ║"
	@echo "╚═════════════════════════════════════════════════════════════╝"

## @description Rebuild the project from scratch
## @usage make rebuild
rebuild:
	@make destroy
	@make install

## @description Start containers
## @usage make up
up:
	docker compose up -d

## @description Stop containers
## @usage make down
down:
	docker compose down --remove-orphans

## @description Remove all containers, volumes and images
## @usage make destroy
destroy:
	docker compose down -v --remove-orphans --rmi all

## @description Restart containers
## @usage make restart
restart:
	@make down
	@make up

## @description Show container status
## @usage make ps
ps:
	docker compose ps

## @description Run tests
## @usage make test
test:
	docker compose exec app php artisan test

## @description Format code
## @usage make format
format:
	docker compose exec app ./vendor/bin/pint

## @description Run static analysis
## @usage make lint
lint:
	docker compose exec app ./vendor/bin/phpstan analyse

## @description Run CI commands (format, lint, test)
## @usage make ci
ci:
	@make format
	@make lint
	@make test

## @description Update IDE helper files
## @usage make ide-apply
ide-apply:
	docker compose exec app php artisan ide-helper:generate
	docker compose exec app php artisan ide-helper:models -N
	docker compose exec app php artisan ide-helper:meta

## @description Enter application container shell
## @usage make app-shell
app-shell:
	docker compose exec app sh

## @description Enter database container shell
## @usage make db-shell
db-shell:
	docker compose exec db mysql -u root -p

## @description Start Tinker
## @usage make tinker
tinker:
	docker compose exec app php artisan tinker

## @description List all routes
## @usage make route
route:
	docker compose exec app php artisan route:list

## @description Clear all caches
## @usage make cache-clear
cache-clear:
	docker compose exec app php artisan optimize:clear

## @description Show PHP Insights
## @usage make insight
insight:
	docker compose exec app php artisan insights

## @description Run database migrations
## @usage make db-migrate
db-migrate:
	docker compose exec app php artisan migrate

## @description Seed the database
## @usage make db-seed
db-seed:
	docker compose exec app php artisan db:seed

## @description Reset and re-seed database
## @usage make db-fresh
db-fresh:
	docker compose exec app php artisan migrate:fresh --seed

## @description Install the project with time and size measurements
## @usage make benchmark
benchmark:
	@echo "╔═════════════════════════════════════════════════════════════╗"
	@echo "║                                                             ║"
	@echo "║              Starting Installation Benchmark                ║"
	@echo "║                                                             ║"
	@echo "╚═════════════════════════════════════════════════════════════╝"
	@echo ""
	@START_TIME=$$(date +%s); \
	START_DISPLAY=$$(date '+%Y-%m-%d %H:%M:%S'); \
	echo "⏰ Start time: $$START_DISPLAY"; \
	echo ""; \
	make install; \
	END_TIME=$$(date +%s); \
	END_DISPLAY=$$(date '+%Y-%m-%d %H:%M:%S'); \
	DURATION=$$((END_TIME - START_TIME)); \
	MINUTES=$$((DURATION / 60)); \
	SECONDS=$$((DURATION % 60)); \
	echo ""; \
	echo "╔═════════════════════════════════════════════════════════════╗"; \
	echo "║                                                             ║"; \
	echo "║                   Benchmark Results                         ║"; \
	echo "║                                                             ║"; \
	echo "╚═════════════════════════════════════════════════════════════╝"; \
	echo ""; \
	echo "⏰ Start time:    $$START_DISPLAY"; \
	echo "⏰ End time:      $$END_DISPLAY"; \
	printf "⏱️  Duration:      %02d:%02d (mm:ss)\n" $$MINUTES $$SECONDS; \
	echo ""; \
	echo "📦 Docker Images:"; \
	docker images --format "table {{.Repository}}\t{{.Tag}}\t{{.Size}}"; \
	echo ""

help:
	@WIDTH=`tput cols 2>/dev/null || echo 80`; \
	awk 'BEGIN { \
		box_width = 65; \
		padding_left = int(('$$WIDTH' - box_width) / 2); \
		if (padding_left < 0) padding_left = 0; \
		padding = sprintf("%*s", padding_left, ""); \
		print padding "╔═════════════════════════════════════════════════════════════╗"; \
		print padding "║                                                             ║"; \
		print padding "║                     Makefile Help Menu                      ║"; \
		print padding "║                                                             ║"; \
		print padding "╚═════════════════════════════════════════════════════════════╝"; \
		print ""; \
	}'
	@awk 'BEGIN { description = ""; usage = ""; } \
		/^## @description/ { description = substr($$0, index($$0, $$3)); } \
		/^## @usage/ { usage = substr($$0, index($$0, $$3)); } \
		/^[a-zA-Z0-9_\-]+:/ { \
			helpCommand = $$1; \
			if (description != "") { \
				printf "  \033[32m%-30s\033[0m %s\n", helpCommand, description; \
				if (usage != "") { \
					printf "  \033[36m%-30s\033[0m %s%s\n\n", "", "▶️ ", usage; \
				} \
			} \
			description = ""; usage = ""; \
		}' $(MAKEFILE_LIST)

# Catch-all target to allow passing arguments
%:
	@:
