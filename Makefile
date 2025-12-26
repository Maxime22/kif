start:
	@if command -v symfony >/dev/null 2>&1; then \
		echo "Lancement avec symfony serve..."; \
		symfony serve; \
	else \
		echo "symfony non trouvé, lancement avec php -S..."; \
		php -S localhost:8000 -t public; \
	fi


console:
	php bin/console

list-services:
	php bin/console debug:container

list-make:
	php bin/console make

list-routes:
	php bin/console debug:router

create-db:
	php bin/console doctrine:database:create

up:
	docker compose up -d

down:
	docker compose down -v
