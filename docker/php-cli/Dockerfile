FROM php:8.4.13-cli-alpine3.22

RUN <<EOF
apk update
apk add --no-cache patch bash
apk del
rm -rf /var/lib/apt/lists/*
EOF

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./php-cli/conf.d /usr/local/etc/php/conf.d

COPY --from=mlocati/php-extension-installer:2.9.8 /usr/bin/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions pdo_pgsql pgsql gd zip sockets exif xdebug redis

COPY --from=composer:2.8.12 /usr/bin/composer /usr/bin/composer
RUN ln -s /usr/bin/composer /usr/bin/c

ARG UID=1000
ARG GID=1000

RUN addgroup -g ${GID} developer \
    && adduser -u ${UID} -G developer -D developer

WORKDIR /var/www/html

USER developer
