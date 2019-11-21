FROM mysql:8.0

ADD test.sql /docker-entrypoint-initdb.d

ENV MYSQL_DATABASE=test \
    MYSQ_ROOT_PASSWORD=root

EXPOSE 3306