FROM php:7.2-fpm

ENV CURRENT_USER user
ENV APP_PATH /var/www/api

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    libzip-dev \
    wget \
    gnupg \
    iputils-ping \
    libpng-dev \
    libfontconfig \
    netcat

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install mysqli pdo pdo_mysql zip bcmath pcntl sockets gd

WORKDIR ${APP_PATH}

COPY docker-entrypoint.sh /usr/local/bin/

RUN chmod 777 /usr/local/bin/docker-entrypoint.sh \
    && ln -s /usr/local/bin/docker-entrypoint.sh

RUN adduser --disabled-password ${CURRENT_USER} && \
    usermod -a -G www-data ${CURRENT_USER}

USER ${CURRENT_USER}

ENTRYPOINT ["docker-entrypoint.sh"]

