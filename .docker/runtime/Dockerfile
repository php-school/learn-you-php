FROM php:8.3-alpine AS build

RUN apk add --no-cache $PHPIZE_DEPS && \
    apk add --no-cache linux-headers

RUN docker-php-ext-install sockets

FROM php:8.3-alpine AS final

COPY --from=build /usr/local/lib/php /usr/local/lib/php
COPY --from=build /usr/local/etc/php /usr/local/etc/php

RUN apk add git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
#owned by whichever user the sandbox runs as. /composer seeds the composer cache volume
RUN mkdir -m 1777 /solution /composer
