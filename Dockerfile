FROM dunglas/frankenphp

RUN install-php-extensions \
    ctype \
    curl \
    dom \
    exif \
    fileinfo \
    filter \
    hash \
    intl \
    mbstring \
    opcache \
    openssl \
    pcre \
    pdo \
    pdo_mysql \
    session \
    tokenizer \
    xml \
    pcntl

COPY . /app

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--env=staging"]
