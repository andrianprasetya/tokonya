#!/bin/sh

docker-compose down
docker network rm tokonya-net
docker volume rm pg-tokonya
docker volume rm redis-vol
