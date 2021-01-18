FROM php:8.0-fpm-alpine

ARG PHP_CONF_DIR="/usr/local/etc"

COPY ./network-socket.pool.conf "${PHP_CONF_DIR}/php-fpm.d/network-socket.pool.conf"
COPY ./unix-domain-socket.pool.conf "${PHP_CONF_DIR}/php-fpm.d/unix-domain-socket.pool.conf"

WORKDIR /repo