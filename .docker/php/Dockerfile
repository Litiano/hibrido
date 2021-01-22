FROM php:7.3-fpm

RUN apt-get update --fix-missing
RUN apt-get install -y \
    openssl \
	curl \
	g++ \
	libpng-dev \
	libjpeg-dev \
	libxml2-dev \
	libxslt-dev \
	libzip-dev \
	build-essential \
	libssl-dev \
	zlib1g-dev \
	libfreetype6-dev \
    nano \
    wget \
    unzip \
    git \
    bash

RUN docker-php-ext-install iconv
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install zip
RUN docker-php-ext-install soap
RUN docker-php-ext-install xsl
RUN docker-php-ext-install sockets
RUN docker-php-ext-install opcache
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --1

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN chown -R www-data:www-data /var/www

RUN echo "memory_limit=4G" >> /usr/local/etc/php/php.ini

USER www-data
