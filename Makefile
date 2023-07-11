current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

.PHONY: build clean deps composer-install composer-update composer-require composer-require-module

IMAGE=php-swift-cart

build: deps
	docker build -t $(IMAGE) .

clean:
	docker rm $$(docker ps -a -q) -f
	docker rmi $(IMAGE)

deps: composer-install

composer-install: CMD=install

composer-update: CMD=update

composer-require: CMD=require
composer-require: INTERACTIVE=-ti --interactive

composer composer-install composer-update composer-require composer-require-module:
	@docker run --rm $(INTERACTIVE) --volume $(current-dir):/app --user $(id -u):$(id -g) \
		composer:2.3.7 $(CMD) \
			--ignore-platform-reqs \
			--no-ansi

test: composer-install
	docker run --rm -v $(PWD):/app -w /app $(IMAGE) vendor/bin/phpunit