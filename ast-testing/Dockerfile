ARG PHP_VERSION=8.5
FROM docker.io/library/php:${PHP_VERSION}-cli

ARG PHP_VERSION

RUN apt-get update && apt-get install -y --no-install-recommends \
    gcc make autoconf libtool pkg-config \
    && rm -rf /var/lib/apt/lists/*

# PHP 8.1: pecl, PHP 8.2+: PIE
RUN if [ "$(echo "${PHP_VERSION}" | cut -d. -f2)" = "1" ]; then \
        pecl install ast && docker-php-ext-enable ast; \
    else \
        curl -sSLf https://github.com/php/pie/releases/latest/download/pie.phar -o /usr/local/bin/pie \
        && chmod +x /usr/local/bin/pie \
        && pie install --no-interaction nikic/php-ast \
        && docker-php-ext-enable ast; \
    fi

RUN php -m | grep ast

WORKDIR /app
