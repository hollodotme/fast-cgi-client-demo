.SILENT:
.PHONY: help

# Based on https://gist.github.com/prwhite/8168133#comment-1313022

## This help screen
help:
	printf "Available commands\n\n"
	awk '/^[a-zA-Z\-\_0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")-1); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "\033[33m%-40s\033[0m %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Update & start complete docker-compose setup
up: dcbuild dcpull dccomposer
	docker-compose up -d --force-recreate nginx
	open "https://fastcgi-demo.hollo.me"
.PHONY: up

## Pull all containers
dcpull:
	docker-compose pull
.PHONY: dcpull

## Build all containers
dcbuild:
	COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose build --pull
.PHONY: dcbuild

## Run composer
dccomposer:
	docker-compose run --rm composer update -o -v --ignore-platform-req=php
.PHONY: dccomposer

status:
	docker-compose ps
.PHONY: status

down:
	docker-compose down
.PHONY: down