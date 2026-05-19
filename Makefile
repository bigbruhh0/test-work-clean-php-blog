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

scss:
	npx sass --no-source-map resources/scss/app.scss public/assets/css/app.css

restart:
	docker compose down
	docker compose up -d --build
