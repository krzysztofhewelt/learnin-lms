@echo off

call npm install
call composer install

call yes|php artisan key:generate
call yes|php artisan jwt:secret

call npm run build

call yes|php artisan migrate
call yes|php artisan db:seed

echo ===========================
echo     Installation done!
echo ===========================
