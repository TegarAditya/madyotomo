FROM dunglas/frankenphp:php8.3-alpine

RUN install-php-extensions \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    gd \
    hash \
    intl \
    mbstring \
    openssl \
    pcntl \
    pcre \
    pdo \
    pdo_mysql \
    session \
    tokenizer \
    xml \
    zip \
    @composer 

RUN addgroup -g 1000 app \
    && adduser -D -u 1000 -G app app

WORKDIR /app

COPY --chown=app:app composer.json composer.lock ./
RUN composer install --no-scripts --prefer-dist --no-dev

COPY --chown=app:app . .

RUN composer dump-autoload \
    --optimize \
    --classmap-authoritative

RUN php artisan optimize

RUN mkdir -p storage bootstrap/cache \
    && chown -R app:app storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER app

ENTRYPOINT ["php"]

CMD ["artisan", "octane:frankenphp", "--workers=2"]