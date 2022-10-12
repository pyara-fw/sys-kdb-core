FROM alpine:latest

LABEL author="Eduardo Luz <eduardo @ eduardo-luz.com>"
LABEL project="sys-kdb-core"
LABEL version="0.0.1"

WORKDIR /app

# Add repos
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories


RUN apk update \
    && apk upgrade \
    && apk add --update util-linux  \
    && apk add bash  curl ca-certificates  \
               openssl openssh git php8 php8-phar php8-json   \
               php8-iconv php8-openssl tzdata openntpd unzip  \
               zip php8-zip php8-fileinfo php8-dom php8-xml   \
               php8-xmlwriter php8-tokenizer php8-session     \
               php8-mbstring php8-sqlite3 php8-xdebug php8-curl \
               php8-pdo php8-mysqli php8-pdo_mysql php8-redis \
               php8-mongodb

RUN cp /usr/bin/php8 /usr/bin/php \
    && rm -f /var/cache/apk/*


# Add Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY ./src/* /app/src
COPY ./tests/* /app/tests
COPY ./composer.* /app/
COPY ./phpunit.* /app/

COPY _docker_/php-dev/50_xdebug.ini /etc/php8/conf.d/

ENTRYPOINT ["sleep","infinity"]
