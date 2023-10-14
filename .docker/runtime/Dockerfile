FROM php:8.2-alpine as build

RUN apk add --no-cache $PHPIZE_DEPS && \
    apk add --no-cache linux-headers

RUN docker-php-ext-install sockets

FROM php:8.2-alpine as final

COPY --from=build /usr/local/lib/php /usr/local/lib/php
COPY --from=build /usr/local/etc/php /usr/local/etc/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
