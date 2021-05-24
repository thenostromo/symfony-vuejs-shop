#!/usr/bin/env bash
sudo apt update
sudo apt install -y libzip-dev libpng-dev libpq-dev libxslt1.1 libxslt1-dev libnss3-tools
sudo apt install -y curl git unzip wget mc nano acl

# POSTGRESQL
sudo apt install postgresql postgresql-contrib
#next: sudo -u postgres psql ,
#next create database ranked_choice;
#next create user rc_admin with encrypted password 'rc1234';
#next grant all privileges on database ranked_choice to rc_admin;

sudo apt-get clean
sudo rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
sudo usermod -u 1000 www-data

sudo php7.4 php7.4-zip php7.4-pdo_pgsql php7.4-gd php7.4-pgsql php7.4-xsl php7.4-curl;

wget https://getcomposer.org/download/2.0.13/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

curl -sL https://deb.nodesource.com/setup_14.x  | bash - && \
    apt-get -y install nodejs && \
    curl -L https://www.npmjs.com/install.sh | sh

wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

sudo mkdir -p /var/www/ranked_choice
sudo chown redlesleys:redlesleys /var/www/ranked_choice
cd /var/www
git clone git@github.com:thenostromo/Symfony5CourseShop.git ranked_choice

sudo setfacl -R -m u:www-data:rX ranked_choice
sudo setfacl -R -m u:www-data:rwX ranked_choice/var/cache todo-symfony/var/log
sudo setfacl -dR -m u:www-data:rwX ranked_choice/var/cache todo-symfony/var/log
export SYMFONY_ENV=prod
composer install --no-dev --optimize-autoloader
php app/console doctrine:schema:create
#migration is not necessary because the application is supposed to be installed with a clean, empty database
php app/console cache:clear --env=prod --no-debug

