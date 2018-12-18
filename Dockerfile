FROM nyanpass/php5.5:5.5-cli

# Install required dependencies
RUN \
    apt-get update && \
    apt-get install -y --no-install-recommends \
        git \
        wget \
        zip unzip

RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

COPY ./src /code/
COPY ./tests /code/

WORKDIR /code

CMD ["tail", "-f", "/dev/null"]