#!/bin/sh

mkdir -p /tmp/nginx_client_body /tmp/nginx_fastcgi /tmp/nginx_proxy

# start php-fpm in background, then nginx in foreground
php-fpm --daemonize
exec nginx -c /home/paas_user/nginx.conf -g 'daemon off;'
