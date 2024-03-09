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
    php artisan passport:keys

RUN chown -R www-data: /app

#upload
RUN echo "upload_max_filesize = 8M\n" \
         "post_max_size = 24M\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

CMD sh /app/docker/startup.sh