FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"
ENV APP_ENV=production
ENV APP_DEBUG=false

WORKDIR /app

# Install PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    tokenizer \
    intl \
    pcntl \
    bcmath \
    exif \
    gd \
    zip \
    redis

# Configure PHP
RUN echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini

# Copy composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Composer Auth
ARG GITHUB_TOKEN
RUN composer config --global --auth github-oauth.github.com ${GITHUB_TOKEN}

# Copy composer files
COPY composer.json composer.lock artisan ./

# Install dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Copy application source
COPY . .

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Create storage link
RUN php artisan storage:link || true

# Optimize Laravel
RUN php artisan route:cache \
    && php artisan view:cache

EXPOSE 80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
