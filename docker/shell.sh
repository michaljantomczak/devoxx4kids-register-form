#!/bin/bash
docker exec -it `docker ps |grep cogitary-polisy-httpd |awk '{ print $1 }'|tail -n+1` /bin/bash
