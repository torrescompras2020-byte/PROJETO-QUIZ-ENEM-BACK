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

# O flag --no-scripts evita que o artisan execute hooks antes do container estar totalmente construído
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
