test:
	docker run --rm -it --volume $(shell pwd):/app php:7.2-cli-alpine /app/vendor/bin/phpunit -c /app/phpunit.xml