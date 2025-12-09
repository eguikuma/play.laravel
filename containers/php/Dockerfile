ARG XDEBUG_INSTALL=true

FROM php:8.3-fpm-alpine AS builder

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zlib-dev

RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    bcmath \
    intl \
    mbstring \
    xml \
    opcache

ARG XDEBUG_INSTALL
RUN pecl install redis && \
    if [ "$XDEBUG_INSTALL" = "true" ]; then pecl install xdebug; fi && \
    rm -rf /tmp/pear ~/.pearrc

FROM php:8.3-fpm-alpine

ARG XDEBUG_INSTALL=true

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk add --no-cache \
    curl \
    libpng \
    libzip \
    icu \
    oniguruma \
    libxml2 \
    libjpeg-turbo \
    freetype \
    fcgi

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php/conf.d/zzz.ini

RUN docker-php-ext-enable pdo_mysql mysqli gd zip bcmath intl mbstring xml opcache redis

COPY xdebug.ini /tmp/xdebug.ini.template
RUN if [ "$XDEBUG_INSTALL" = "true" ]; then \
        docker-php-ext-enable xdebug && \
        cat /tmp/xdebug.ini.template >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi && \
    rm -f /tmp/xdebug.ini.template

COPY www.conf /usr/local/etc/php-fpm.d/zzz.conf

RUN addgroup -g 1000 local && \
    adduser -D -u 1000 -G local local

RUN mkdir -p /var/www/html && \
    chown -R local:local /var/www/html

RUN chown -R local:local /var/log /usr/local/etc/php-fpm.d && \
    touch /usr/local/var/log/php-fpm.log && \
    chown local:local /usr/local/var/log/php-fpm.log

WORKDIR /var/www/html

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

EXPOSE 9000

USER local

CMD ["php-fpm"]
