#!/bin/bash

docker rm -f `docker ps -a |grep cogitary-polisy |awk '{ print $1 }'|tail -n+1`
#docker rmi `docker images |grep 'idealna-ekspozycja-httpd'|awk '{ print $3 }'`
#docker rmi `docker images |grep 'idealna-ekspozycja-mysql'|awk '{ print $3 }'`
