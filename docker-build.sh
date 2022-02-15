#!/bin/sh

docker-compose down
docker network create tokonya-net
docker volume create pg-tokonya
docker volume create redis-vol
docker-compose up -d
