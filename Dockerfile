FROM  php:7.4.33-apache-bullseye
LABEL Maintainer="Daniele Masotti <d.masotti@alfagroup.it>"
LABEL Description=" container with apache & PHP 8.3 based on Alpine Linux."

ARG MYSQL_HOST
ENV MYSQL_HOST ${MYSQL_HOST:-"mysql"}

ARG MYSQL_USER
ENV MYSQL_USER ${MYSQL_USER:-"mysql"}

ARG MYSQL_PASSWORD
ENV MYSQL_PASSWORD ${MYSQL_PASSWORD:-"password"}

ARG MYSQL_DATABASE
ENV MYSQL_DATABASE ${MYSQL_DATABASE:-"tenant1"}

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

#COPY --chown=nobody src/ /var/www/html/
COPY src/ /var/www/html/
EXPOSE 80
RUN chmod -R a+r /var/www/html/

