#!/bin/bash

mysql=`docker ps -a |grep cogitary-polisy-mysql |awk '{ print $1 }'`
httpd=`docker ps -a |grep cogitary-polisy-httpd |awk '{ print $1 }'`

if [ -z "`docker ps |grep cogitary-polisy-mysql |awk '{ print $1 }'`" ]
then
	if [ -z "$mysql" ]
	then
	docker run -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=db -d -p 3032:3306 --name cogitary-polisy-mysql -t cogitary-polisy-mysql
	else
	docker start $mysql
	fi
fi

if [ -z "`docker ps |grep cogitary-polisy-httpd |awk '{ print $1 }'`" ]
then
	if [ -z "$httpd" ]
	then
	docker run -d -p 2032:80 -e XDEBUG_CONFIG="remote_host=192.168.1.113" --privileged -v `pwd`:/var/www/html --link cogitary-polisy-mysql -i -t cogitary-polisy-httpd
	else
	docker start $httpd
	fi
fi
