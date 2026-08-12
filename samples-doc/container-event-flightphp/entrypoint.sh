#!/bin/sh

cd /home/paas_user
php -S 0.0.0.0:8000 -t src/public
