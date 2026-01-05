FROM php:8.0-apache

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache設定
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# 作業ディレクトリ
WORKDIR /var/www/html

# パーミッション設定
RUN chown -R www-data:www-data /var/www/html
