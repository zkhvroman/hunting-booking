FROM php:8.4.13-fpm-alpine3.22

RUN <<EOF apk update
apk add --no-cache patch
apk del
rm -rf /var/lib/apt/lists/*
EOF

RUN mv ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini
COPY ./common/php/conf.d /usr/local/etc/php/conf.d

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions pdo_pgsql pgsql gd zip sockets exif xdebug redis

COPY ./php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

ARG UID=1000
ARG GID=1000

RUN <<EOF
apk update
apk add --no-cache shadow
usermod -u ${UID} www-data
groupmod -g ${GID} www-data
apk del
rm -rf /var/lib/apt/lists/*
EOF

WORKDIR /var/www/html
