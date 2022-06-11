FROM php:8.1-fpm

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        libicu-dev \
        curl \
        libcurl4-openssl-dev \
        nginx

RUN docker-php-ext-install -j$(nproc) intl curl fileinfo pdo pdo_mysql opcache

RUN pecl install redis-5.3.4 && docker-php-ext-enable redis

RUN mkdir -m 777 /var/run/php-fpm

COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./sites/ /etc/nginx/sites-available
COPY ./conf.d/ /etc/nginx/conf.d
COPY ./entrypoint.sh /etc/entrypoint.sh

WORKDIR /var/www

ENV TZ=Europe/Prague
ENV OPCACHE_ENABLE=1
ENV OPCACHE_MEMORY_CONSUMPTION=192
ENV OPCACHE_INTERNED_STRINGS_BUFFER=16
ENV OPCACHE_MAX_ACCELERATED_FILES=10000
ENV OPCACHE_MAX_WASTED_PERCENTAGE=10
ENV OPCACHE_VALIDATE_TIMESTAMPS=1
ENV OPCACHE_REVALIDATE_FREQ=0

EXPOSE 80

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
