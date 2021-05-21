FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y libzip-dev libpng-dev libpq-dev libxslt1.1 libxslt1-dev curl git unzip wget mc nano --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    usermod -u 1000 www-data

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install zip pdo_pgsql gd pgsql xsl;

RUN wget https://getcomposer.org/download/2.0.13/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_14.x  | bash - && \
    apt-get -y install nodejs && \
    curl -L https://www.npmjs.com/install.sh | sh

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

#COPY /supervisor /etc/supervisor/conf.d

#RUN chmod -R 755 /etc/supervisor/conf.d/*

WORKDIR /var/www

 #CMD ["bash", "-c", "cp /var/www/.docker/dev.env /var/www/.env"]

EXPOSE 9000
