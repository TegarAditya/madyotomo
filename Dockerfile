FROM dunglas/frankenphp:php8.3-alpine AS php-vendor
WORKDIR /app

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

COPY composer.json composer.lock ./
RUN composer install --no-scripts --prefer-dist --no-dev --no-interaction --optimize-autoloader


FROM node:22-alpine AS node-builder
WORKDIR /app

COPY package*.json ./

COPY vite.config.js postcss.config.js ./

RUN npm ci --silent

COPY resources/ ./resources/

# Vite imports Filament theme files from /vendor path.
COPY --from=php-vendor /app/vendor ./vendor

RUN npm run build

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

# Install PHP deps (no dev) using Composer
COPY --chown=app:app composer.json composer.lock ./
RUN composer install --no-scripts --prefer-dist --no-dev --no-interaction --optimize-autoloader

# Copy application files
COPY --chown=app:app . .

# Copy built assets from the node-builder stage
# Adjust paths if your build outputs elsewhere
COPY --from=node-builder /app/public ./public

# optimize and dump autoloads
RUN composer dump-autoload --optimize --classmap-authoritative \
    && php artisan optimize

RUN mkdir -p storage bootstrap/cache \
    && chown -R app:app storage bootstrap/cache public/build \
    && chmod -R 775 storage bootstrap/cache public/build

USER app

ENTRYPOINT ["php"]
CMD ["artisan", "octane:frankenphp", "--workers=2"]