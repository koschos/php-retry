# Use this Dockerfile only for dev purposes
FROM php:7.2-cli

RUN pecl install \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

COPY ./src /code/
COPY ./tests /code/

WORKDIR /code

CMD ["tail", "-f", "/dev/null"]