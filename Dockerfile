FROM node:20.4.0-alpine3.17 as npm
CMD ["npm", "start"]

RUN mkdir -p /app
COPY package*.json vite.config.js tailwind.config.js postcss.config.js .env /app/
COPY resources/ /app/resources/
COPY lang/ app/lang/
WORKDIR /app

RUN npm install && npm run build


#Server Dependencies
FROM composer:2.5.8 as composer

WORKDIR /app

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


# PHP
FROM php:8.2-apache as base

# Install dependencies
RUN apt-get update && apt-get install -y \
        build-essential \
        libpng-dev \
        libonig-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        libzip-dev \
        git \
        zip \
        jpegoptim optipng pngquant gifsicle \
        unzip \
		&& apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
   	&& docker-php-ext-install -j$(nproc) gd

RUN sed -i -e "s/html/html\/public/g" \
    /etc/apache2/sites-enabled/000-default.conf

COPY ./Docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# enable apache mod_rewrite
RUN a2enmod rewrite

COPY . /var/www/html
COPY --from=composer /app/vendor/ /var/www/html/vendor/
COPY --from=npm /app/node_modules /var/www/html/node_modules/
COPY --from=npm /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

RUN php artisan key:generate
RUN php artisan jwt:secret

EXPOSE 80
