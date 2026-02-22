.PHONY: up down install update

up:
	docker compose up --build -d

down:
	docker compose down

install:
	docker compose run --rm -v $(PWD)/frontend:/app frontend npm install
	docker compose run --rm -v $(PWD)/backend:/app backend composer install

update:
	docker compose run --rm -v $(PWD)/frontend:/app frontend npm update
	docker compose run --rm -v $(PWD)/backend:/app backend composer update
