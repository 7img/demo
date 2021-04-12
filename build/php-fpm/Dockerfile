FROM alpine:3.13

RUN apk add --no-cache \
    coreutils \
    php8-fpm \
    php8-ctype \
    php8-curl \
    php8-dom \
    php8-gd \
    php8-iconv \
    php8-json \
    php8-intl \
    php8-fileinfo\
    php8-mbstring \
    php8-opcache \
    php8-openssl \
    php8-pdo \
    php8-pdo_pgsql \
    php8-xml \
    php8-zlib \
    php8-phar \
    php8-tokenizer \
    php8-session \
    php8-simplexml \
    php8-zip \
    php8-xmlwriter \
    php8-pecl-apcu \
    make \
    curl \
    bash

# Allow to enable php8-xdebug
ARG ENABLE_PHP_XDEBUG
RUN if [[ "$ENABLE_PHP_XDEBUG" == "1" ]]; then \
        apk add --no-cache php8-pecl-xdebug; \
    fi

RUN curl -sS https://getcomposer.org/installer | tee composer-setup.php \
    && php8 composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer \
    && ln -s /usr/bin/php8 /usr/local/bin/php

# RUN wget https://get.symfony.com/cli/installer -O - | bash
# RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY ./build/php-fpm/symfony.ini /etc/php8/conf.d/
COPY ./build/php-fpm/symfony.ini /etc/php8/cli/conf.d/
COPY ./build/php-fpm/xdebug.ini  /etc/php8/conf.d/
COPY ./build/php-fpm/symfony.pool.conf /etc/php8/php-fpm.d/

WORKDIR /var/www/symfony

CMD ["php-fpm8", "-F"]

EXPOSE 9001
