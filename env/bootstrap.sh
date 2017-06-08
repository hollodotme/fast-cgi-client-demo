#!/usr/bin/env bash

sudo apt-get update
sudo apt-get install wkhtmltopdf xvfb -y

sudo touch /var/run/php-uds.sock && sudo chown www-data:www-data /var/run/php-uds.sock

sudo ln -sf /vagrant/env/php-fpm/7.1/network-socket.pool.conf /etc/php/7.1/fpm/pool.d/network-pool.conf
sudo ln -sf /vagrant/env/php-fpm/7.1/unix-domain-socket.pool.conf /etc/php/7.1/fpm/pool.d/unix-domain-socket-pool.conf

sudo rm -f /etc/nginx/sites-enabled/default
sudo ln -sf /vagrant/env/nginx/dist.conf /etc/nginx/sites-enabled/default

sudo service php7.1-fpm restart
sudo service nginx restart
