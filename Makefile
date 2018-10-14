#---------------------------
# Docker controls
#---------------------------

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose up -d --build

s:
	docker-compose ps

#---------------------------
# Application
#---------------------------

migrate:
	docker-compose exec php-cli php artisan migrate

seed:
	docker-compose exec php-cli php artisan db:seed

tinker:
	docker-compose exec php-cli php artisan tinker

test:
	@docker-compose exec php-cli vendor/bin/phpunit

refresh:
	docker-compose exec php-cli php artisan migrate:refresh

#---------------------------
# Front-end
#---------------------------

assets:
	docker-compose exec node yarn install

watch:
	docker-compose exec node yarn watch

perm:
	sudo chown ${USER}:${USER} app/ database/ resources/ tests/ -R
	sudo chmod -R 777 bootstrap/cache
	sudo chmod -R 777 storage
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R

#---------------------------
# Installation
#---------------------------

laravel:
	# Create laravel app
	# Composer does not allow creating inside existing directory
	# So first laravel will be installed into laravel directory and then moved from
	docker-compose exec php-cli composer create-project --prefer-dist laravel/laravel laravel
	sudo chown ${USER}:${USER} laravel/ -R
	sudo mv laravel/** .
	# Move dot-starting files
	sudo mv laravel/.??* .
	sudo rm -r laravel
	sudo chmod -R 777 bootstrap/cache
	sudo chmod -R 777 storage
	# Install predis to use redis as service
	docker-compose exec php-cli composer require predis/predis
