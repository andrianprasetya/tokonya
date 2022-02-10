#!/bin/sh

docker-compose down
docker system prune -f
docker builder prune -f
docker volume prune -f
docker system prune -a --force
