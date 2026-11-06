FROM php:8.4-cli

# Instala dependências do sistema e extensão PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instala dependências incluindo dev para garantir compatibilidade com providers cadastrados
RUN composer install --optimize-autoloader --ignore-platform-reqs --no-scripts

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
