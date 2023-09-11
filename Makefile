install: setup-docker install-app

setup-docker:
	docker-compose up -d --build

start-docker:
	docker-compose start

stop-docker:
	docker-compose stop

install-app:
	docker compose exec app composer install

docker-create-db:
	docker compose exec app php bin/console doctrine:database:create

docker-create-migrate:
	docker compose exec app php bin/console make:migration

docker-start-migrate:
	docker compose exec app php bin/console doctrine:migrations:migrate
