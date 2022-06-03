create-app:
	composer require symfony/console

start-debug:
	export XDEBUG_MODE=debug XDEBUG_SESSION=1

# execute it before test
clear-cache:
	rm -rf var