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

# Instala dependências
RUN composer install --optimize-autoloader --ignore-platform-reqs --no-scripts

EXPOSE 10000

# Executa o servidor PHP diretamente na pasta public com index.php como roteador
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public", "public/index.php"]
