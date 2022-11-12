PORT ?= 8000

start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 public
	composer exec --verbose phpcs -- --standard=PSR12 src
	# composer exec --verbose phpstan -- --level=8 analyse public

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml