FROM php:8.4-apache

# نصب افزونه‌ها
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# نصب Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# فعال کردن mod_rewrite
RUN a2enmod rewrite

# تغییر DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY . .

# نصب پکیج‌ها
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction

# مجوزها
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# اجرا با config:clear
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force --graceful && \
    php artisan db:seed --force || true && \
    apache2-foreground