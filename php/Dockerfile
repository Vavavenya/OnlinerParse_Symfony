FROM php:7.4-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
    libonig-dev \
    libxml2-dev \
    && pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install \
    gd \
    bz2 \
    intl \
    bcmath \
    opcache \
    mbstring \
    xml \
    calendar \
    pdo \
    pdo_mysql && \
    docker-php-ext-enable xdebug

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd

RUN docker-php-ext-enable pdo_mysql pdo

WORKDIR /var/www/trial
