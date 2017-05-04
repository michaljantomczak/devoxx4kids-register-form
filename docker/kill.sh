#!/bin/bash

docker kill `docker ps |grep cogitary-polisy |awk '{ print $1 }' |tail -n+1`
