# Usar una imagen base con PHP y Composer
FROM php:8.2-cli

# Instalar dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    # Extensiones para PostgreSQL y otras posibles necesidades
    && docker-php-ext-install pdo pdo_pgsql \
    # Limpiar caché para reducir tamaño de imagen
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar solo los archivos necesarios para instalar dependencias primero (optimización de caché)
COPY ../composer.json ./

# Instalar dependencias de producción (ajusta según tus necesidades)
RUN composer install --no-dev --no-scripts --no-autoloader && composer clear-cache

# Copiar todo el código fuente (incluyendo .env si es necesario)
COPY . .

RUN git config --global --add safe.directory /var/www/html

# Exponer el puerto de la aplicación
EXPOSE 8000

# Comando para ejecutar la aplicación (ajusta según tu entrypoint)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]