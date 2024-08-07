FROM dunglas/frankenphp

RUN install-php-extensions \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    hash \
    mbstring \
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
