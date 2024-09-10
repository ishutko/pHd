#!/bin/bash

docker exec -i "phd-app" sh -c "composer install"
docker exec -i "phd-app" sh -c "php artisan clear-compiled"
docker exec -i "phd-app" sh -c "php artisan config:clear"
docker exec -i "phd-app" sh -c "php artisan cache:clear"
docker exec -i "phd-app" sh -c "php artisan view:clear"
docker exec -i "phd-app" sh -c "php artisan route:clear"
docker exec -i "phd-app" sh -c "sudo composer dump-autoload"
docker exec -i "phd-app" sh -c "php artisan optimize"
docker exec -i "phd-app" sh -c "php artisan dump-autoload"
