FROM php:8.1-fpm-alpine

RUN apk add --no-cache nginx mysql-client curl unzip \
    && docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html/
COPY nginx-backend.conf /etc/nginx/http.d/default.conf

RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

RUN chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 80

CMD ["/var/www/html/docker-entrypoint.sh"]
