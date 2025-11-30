FROM php:8.2-fpm

ARG PUID=1000
ARG PGID=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_pgsql bcmath gd zip pcntl sockets \
    && npm install -g chokidar-cli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${PGID} dockeruser \
    && useradd -u ${PUID} -g ${PGID} -m dockeruser \
    && mkdir -p /var/www/html /tmp/composer \
    && chown -R dockeruser:dockeruser /var/www/html /tmp/composer

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/composer

USER dockeruser
