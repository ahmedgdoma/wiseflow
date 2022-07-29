composerFile := -f docker/docker-compose.yml
php_container := wise_php
composer-install:
	docker-compose $(composerFile) exec $(php_container) bash -c "cd /app/ && composer install -nv"
doctrine-migrate:
	docker-compose $(composerFile) exec $(php_container) bash -c "symfony console d:mi:mi -n"
fixtures-load:
	docker-compose $(composerFile) exec $(php_container) bash -c "symfony console d:f:l -n"

run-php:
	docker-compose $(composerFile) up -d

run:
	make env
	make run-php
	make composer-install
	make doctrine-migrate
	make fixtures-load

env:
	touch app/.env.local
	cat app/.env > app/.env.local

stop:
	docker-compose $(composerFile) down
