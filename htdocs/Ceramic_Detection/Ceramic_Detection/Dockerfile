FROM php:8.2-apache

# Cài đặt các phụ thuộc
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Sao chép mã nguồn
COPY . .

# Cài đặt thư viện Laravel
RUN if [ -f composer.json ]; then composer install --no-interaction --optimize-autoloader; fi

# Cấp quyền
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Kích hoạt mod_rewrite
RUN a2enmod rewrite

# Cấu hình Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Mở port 80
EXPOSE 80

# Khởi động Apache
CMD ["apache2-foreground"]