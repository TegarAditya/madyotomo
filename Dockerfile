FROM dunglas/frankenphp:php8.2-alpine

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
    zip

RUN apk add --no-cache nodejs npm

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

COPY . /app

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
