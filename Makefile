up:
	docker compose up -d --build

down:
	docker compose down

composer-install:
	docker compose run --rm php composer install

restart:
	docker compose down
	docker compose up -d --build

