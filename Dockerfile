# Gunakan base image resmi PHP dengan Apache
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libxml2-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file ke container
COPY . .

# Set permission agar Laravel bisa menulis ke storage dan cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Laravel public directory sebagai root Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Ubah konfigurasi Apache ke Laravel public folder
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Port yang akan dibuka
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
