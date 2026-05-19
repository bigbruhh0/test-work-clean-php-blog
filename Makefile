up:
	docker compose up -d --build

down:
	docker compose down

composer-install:
	docker compose run --rm php composer install

migrate:
	docker compose run --rm php php bin/console migrate

seed:
	docker compose run --rm php php bin/console seed

restart:
	docker compose down
	docker compose up -d --build
