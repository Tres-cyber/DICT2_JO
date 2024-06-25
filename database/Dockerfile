FROM mysql:8.0.32
ENV MYSQL_RANDOM_ROOT_PASSWORD=yes MYSQL_USER=admin MYSQL_DATABASE=adventour
copy *.csv /var/lib/mysql-files/
copy *.sh *.sql /docker-entrypoint-initdb.d/
