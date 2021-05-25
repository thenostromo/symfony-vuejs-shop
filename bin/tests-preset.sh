#!/usr/bin/env bash
apt install -y libzip-dev libpng-dev libpq-dev libxslt1.1 libxslt1-dev libnss3-tools --no-install-recommends
apt install php7.4 php7.4-zip php7.4-gd php7.4-pgsql php7.4-xsl php7.4-curl php7.4-mbstring php7.4-intl php7.4-fpm
wget https://getcomposer.org/download/2.0.13/composer.phar
mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer
wget https://get.symfony.com/cli/installer -O - | sudo bash
mv ~/.symfony/bin/symfony /usr/local/bin/symfony
composer install --no-dev --optimize-autoloader
