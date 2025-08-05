start:
	php -S localhost:8000 -t public

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
