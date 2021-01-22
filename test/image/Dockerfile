FROM php:7.4-fpm

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN mkdir -p /home/www-data
RUN chown -R www-data:www-data /home/www-data
RUN usermod -d /home/www-data www-data

USER www-data