FROM postgres:15-alpine
ENV POSTGRES_USER=root
ENV POSTGRES_PASSWORD=12345
ENV POSTGRES_DB=mi_base_de_datos
COPY init.sql /docker-entrypoint-initdb.d/