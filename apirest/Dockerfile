FROM php:8.1-fpm-alpine

# Instalar PostgreSQL client
RUN apk add --no-cache postgresql-client
CMD ["tail", "-f", "/dev/null"]
# Resto de tu configuración (copiar código, instalar dependencias, etc.)
COPY . /var/www/html
WORKDIR /var/www/html