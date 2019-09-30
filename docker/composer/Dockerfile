FROM sandrokeil/php:5.6-cli

RUN cd /tmp && curl -sS https://getcomposer.org/installer | php && \
    mv /tmp/composer.phar /usr/local/bin/composer && \
    apt-get update && apt-get install -y zlib1g-dev  libzip-dev && \
    docker-php-ext-install zip && \
    composer global require hirak/prestissimo

RUN cd /tmp && curl https://phar.phpunit.de/phpunit.phar > phpunit.phar && \
        chmod +x phpunit.phar && \
        mv /tmp/phpunit.phar /usr/local/bin/phpunit

RUN apt-get -y install php-net-socket/oldoldstable && \
    docker-php-ext-install sockets

WORKDIR /app
ENV TINI_VERSION v0.18.0
ADD https://github.com/krallin/tini/releases/download/${TINI_VERSION}/tini /sbin/tini
RUN chmod +x /sbin/tini
COPY docker-entrypoint.sh /
ENTRYPOINT ["/bin/sh", "/docker-entrypoint.sh"]

CMD ["composer"]