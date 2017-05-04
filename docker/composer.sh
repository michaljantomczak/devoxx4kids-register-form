#!/bin/bash
command='cd /var/www/html; php composer.phar '$@
docker exec -it `docker ps |grep cogitary-polisy-httpd |awk '{ print $1 }'|tail -n+1` sh -c "$command"
