#!/bin/bash

npm install
composer install

yes|php artisan key:generate
yes|php artisan jwt:secret

npm run build

yes|php artisan migrate
yes|php artisan db:seed

echo ===========================
echo     Installation done!
echo ===========================
