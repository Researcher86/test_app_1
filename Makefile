up:
	docker-compose up -d

down:
	docker-compose down -v

init:
	composer app-init

test:
	XDEBUG_CONFIG=client_host=172.17.0.1 PHP_IDE_CONFIG=serverName=php-cli composer test