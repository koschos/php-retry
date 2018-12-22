# Use this Dockerfile only for dev purposes
FROM prooph/php:7.2-cli-xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

COPY ./src /code/
COPY ./tests /code/

WORKDIR /code

CMD ["tail", "-f", "/dev/null"]