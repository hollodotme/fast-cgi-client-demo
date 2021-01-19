FROM php:8.0-fpm-alpine

ARG PHP_CONF_DIR="/usr/local/etc"

RUN set -ex \
	&& apk update \
	&& apk upgrade \
	&& apk add --no-cache procps

COPY ./dynamic-network-socket.pool.conf "${PHP_CONF_DIR}/php-fpm.d/www.conf"
COPY ./on-demand-unix-domain-socket.pool.conf "${PHP_CONF_DIR}/php-fpm.d/on-demand-unix-domain-socket.pool.conf"
COPY ./static-network-socket.pool.conf "${PHP_CONF_DIR}/php-fpm.d/static-network-socket.pool.conf"

WORKDIR /repo