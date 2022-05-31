create-app:
	composer require symfony/console

start-debug:
	export XDEBUG_MODE=debug XDEBUG_SESSION=1

run-tests:
	./vendor/bin/phpunit tests