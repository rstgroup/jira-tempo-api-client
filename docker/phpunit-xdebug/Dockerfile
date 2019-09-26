FROM sandrokeil/php:5.6-cli-xdebug

RUN cd /tmp && curl -sS https://getcomposer.org/installer | php && \
    mv /tmp/composer.phar /usr/local/bin/composer

RUN cd /tmp && curl https://phar.phpunit.de/phpunit.phar > phpunit.phar && \
        chmod +x phpunit.phar && \
        mv /tmp/phpunit.phar /usr/local/bin/phpunit