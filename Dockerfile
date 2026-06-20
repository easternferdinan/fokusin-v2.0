FROM composer:2 AS composer_stage
FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    unzip \
  && docker-php-ext-install -j$(nproc) intl zip \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache: set document root to public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
  && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy composer binary
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

# Copy dependency manifests first (layer caching)
COPY composer.json composer.lock /var/www/html/

# Install dependencies (including dev)
RUN composer install --no-interaction

# Copy application source
COPY . /var/www/html/

# Set writable directory permissions
RUN chown -R www-data:www-data /var/www/html/writable

EXPOSE 80

