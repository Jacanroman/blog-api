FROM php:8.3.11-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libxpm-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    fcgiwrap \
    libmcrypt-dev \
    libonig-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath opcache

# Install Redis PHP extension
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

RUN mkdir -p /var/www/.config/psysh \
    && chown -R www-data:www-data /var/www/.config

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
