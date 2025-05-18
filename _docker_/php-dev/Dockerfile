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
    && apk add bash  curl ca-certificates openssl openssh git \
                php84 php84-phar php84-json   \
               php84-iconv php84-openssl tzdata openntpd unzip  \
               zip php84-zip php84-fileinfo php84-dom php84-xml   \
               php84-xmlwriter php84-tokenizer php84-session     \
               php84-mbstring php84-sqlite3 php84-xdebug php84-curl \
               php84-pdo php84-mysqli php84-pdo_mysql php84-redis \
               php84-mongodb

RUN cp /usr/bin/php84 /usr/bin/php \
    && rm -f /var/cache/apk/*


# # Add Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY ./src/* /app/src
COPY ./tests/* /app/tests
COPY ./composer.* /app/
COPY ./phpunit.* /app/

COPY _docker_/php-dev/50_xdebug.ini /etc/php8/conf.d/

ENTRYPOINT ["sleep","infinity"]
