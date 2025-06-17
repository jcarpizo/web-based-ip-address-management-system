FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update \
 && apt-get install -y \
       default-mysql-client \
       libzip-dev \
       unzip \
       curl \
       rsync \
       libicu-dev \
       libonig-dev \
       libxml2-dev \
       build-essential \
       vim \
 && curl -fsSL https://deb.nodesource.com/setup_current.x | bash - \
 && apt-get install -y nodejs \
 && docker-php-ext-configure intl \
 && docker-php-ext-install \
       pdo_mysql \
       zip \
       mbstring \
       bcmath \
       sockets \
       xml \
       ctype \
       fileinfo \
       intl \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/composer

RUN curl -sS https://phar.phpunit.de/phpunit-10.phar -o /usr/local/bin/phpunit \
 && chmod +x /usr/local/bin/phpunit

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
