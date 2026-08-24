test:
	vendor/bin/phpstan analyse --level 9 src tests bin
	vendor/bin/phpunit tests

style:
	vendor/bin/php-cs-fixer fix
