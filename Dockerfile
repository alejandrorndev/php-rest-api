FROM php:8.2-cli


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

COPY ../composer.json ./

RUN composer install --no-dev --no-scripts --no-autoloader && composer clear-cache

COPY . .

RUN git config --global --add safe.directory /var/www/html

#puerto de la aplicación
EXPOSE 8000

# Comando para ejecutar la aplicación
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]