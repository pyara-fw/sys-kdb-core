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
               openssl openssh git php7 php7-phar php7-json   \
               php7-iconv php7-openssl tzdata openntpd unzip  \
               zip php7-zip php7-fileinfo php7-dom php7-xml   \
               php7-xmlwriter php7-tokenizer php7-session     \
               php7-mbstring php7-sqlite3 php7-xdebug php7-curl \
               php7-pdo php7-mysqli php7-pdo_mysql php7-ssh2 php7-redis

RUN cp /usr/bin/php7 /usr/bin/php \
    && rm -f /var/cache/apk/*


# Add Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY ./src/* /app/src
COPY ./tests/* /app/tests
COPY ./composer.* /app/
COPY ./phpunit.* /app/

COPY _docker_/php-dev/50_xdebug.ini /etc/php7/conf.d/

ENTRYPOINT ["sleep","infinity"]
