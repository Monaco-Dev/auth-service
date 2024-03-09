FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app
COPY ./src /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --optimize-autoloader --no-dev &&  \
    docker-php-ext-install pdo pdo_mysql && \
    php artisan passport:keys && \
    php artisan telescope:install

ADD ./docker/file-upload.ini /usr/local/etc/php/conf.d/file-upload.ini

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh