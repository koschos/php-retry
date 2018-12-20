FROM php:5.6-cli-alpine

RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

COPY ./src /code/
COPY ./tests /code/

WORKDIR /code

CMD ["tail", "-f", "/dev/null"]