FROM php:8.4-apache

# نصب افزونهها
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# فعال کردن mod_rewrite
RUN a2enmod rewrite

# تغییر DocumentRoot به public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# کپی پروژه
WORKDIR /var/www/html
COPY . .

# نصب پکیجها
RUN composer install --no-dev --optimize-autoloader

# مجوزها
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# پورت
EXPOSE 80

# اسکریپت اجرا
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    apache2-foreground