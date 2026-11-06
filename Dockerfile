FROM php:8.4-cli

# Instala extensões necessárias e dependências do sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Ignora restrições estritas de versão da plataforma durante o build
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
