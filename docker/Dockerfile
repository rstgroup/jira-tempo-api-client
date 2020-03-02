FROM php7.3-cli-composer-xdebug-alpine3.9:latest
RUN apk add zip && \
 apk add --no-cache libzip-dev && docker-php-ext-configure zip --with-libzip=/usr/include && docker-php-ext-install zip