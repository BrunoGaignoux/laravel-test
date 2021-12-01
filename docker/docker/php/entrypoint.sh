#!/bin/sh

composer dump-autoload --optimize
find ./storage -type d -exec chmod 0755 {} \; 
find ./storage -type f -exec chmod 0644 {} \; 

if [ "$RUN_MIGRATIONS" != "" ]; then
  wait-for db:3306
  php artisan migrate
fi

$@