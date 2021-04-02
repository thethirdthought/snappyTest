#!/bin/bash

d /var/www/html/ci/


chmod -R 777 storage/


service nginx start
#service supervisor start
service php8.0-fpm start
tail -f /dev/null

