FROM dunglas/frankenphp:php8.3-alpine

RUN install-php-extensions \
    ctype \
    curl \
    dom \
    exif \
    fileinfo \
    filter \
    gd \
    hash \
    intl \
    mbstring \
    opcache \
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

WORKDIR /app

COPY . /app

RUN composer install --optimize-autoloader

RUN php artisan optimize

ENTRYPOINT ["php"]

CMD ["artisan", "octane:frankenphp", "--workers=1", "--max-requests=1"]