composerFile := -f docker/docker-compose.yml
composer-install:
	docker-compose $(composerFile) run php bash -c "cd /app/ext; composer install"

doctrine-migrate:
	until docker-compose $(composerFile) run php bash -c "cd /app/Uniwise/Symfony; ./console doctrine:schema:update --force"; do sleep 1; done

run-php: composer-install
	docker-compose $(composerFile) up -d
	@docker-compose $(composerFile) exec -uroot php chown -R www-data:www-data /var/cache/symfony

run: run-php doctrine-migrate

stop:
	docker-compose down
