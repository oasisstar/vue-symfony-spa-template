#!/usr/bin/env bash

until nc -z -v -w30 db 3306
do
  echo "Waiting for database connection..."
  # wait for 5 seconds before check again
  sleep 5
done

composer update symfony/flex

make init

docker-php-entrypoint php-fpm
