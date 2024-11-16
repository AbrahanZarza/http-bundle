FROM php:8.3-fpm-alpine

EXPOSE 8000

RUN apk --update add \
    alpine-sdk \
    openssl-dev \
    php83-pear \
    php83-dev \
    linux-headers \
    && rm -rf /var/cache/apk/*

RUN pecl channel-update pecl.php.net

ENV TZ=${TZ}

RUN apk add --update --upgrade tzdata autoconf g++ make \
    && ln -s /usr/share/zoneinfo/$TZ /etc/localtime \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer