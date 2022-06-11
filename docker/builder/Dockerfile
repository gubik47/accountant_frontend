FROM php:8.1-cli

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        libicu-dev \
        curl git zip unzip \
        libcurl4-openssl-dev \
        nodejs \
        gnupg \
        default-mysql-client

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y yarn

RUN docker-php-ext-install -j$(nproc) intl curl fileinfo pdo pdo_mysql

RUN pecl install redis-5.3.4 && docker-php-ext-enable redis

COPY ./php.ini /usr/local/etc/php/php.ini

COPY ./composer.phar /usr/local/bin/composer

RUN touch /.yarnrc ; chmod 777 /.yarnrc
RUN mkdir -m 777 /.yarn
RUN mkdir -m 777 /.composer

ENV COMPOSER_HOME=/.composer
ENV YARN_CACHE_FOLDER=/.yarn/cache
ENV YARN_GLOBAL_FOLDER=/.yarn/global

# vychozi timezone
ENV TZ=Europe/Prague

WORKDIR /var/www
