FROM ubuntu:latest

WORKDIR /var/www

# Install php
RUN apt-get update && apt-get install -y software-properties-common && \
    add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install -y php7.3 \
    php7.3-cli \
    php7.3-common \
    php7.3-fpm

# Install extension for php
RUN apt-get update && apt-get install -y php-pear php7.3-curl php7.3-dev \
    php7.3-gd php7.3-mbstring php7.3-zip php7.3-mysql \
    php7.3-xml php7.3-fpm libapache2-mod-php7.3 \
    php7.3-imagick php7.3-recode php7.3-tidy \
    php7.3-xmlrpc php7.3-intl \
    zip unzip curl git nano

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Mysql
RUN apt-get update && apt-get install -y mysql-server

COPY .docker/data/k3mn_shop.sql data/

# Install Node & npm
RUN apt-get update && apt-get install -y nodejs npm
RUN npm install -g laravel-echo-server

# Install Redis
RUN apt-get update && apt-get install -y redis-server

# Install Supervisor
RUN apt-get update && apt-get install -y supervisor

COPY .docker/conf/supervisor_conf /etc/supervisor/conf.d

# Install Nginx
RUN apt-get update && apt-get install -y nginx
RUN rm /etc/nginx/sites-enabled/default
COPY .docker/conf/nginx_conf/default /etc/nginx/sites-enabled

# Copy code into root directory webserver
COPY src .

RUN chown -R www-data:www-data /var/www

RUN npm install && composer install

# RUN supervisorctl reread && supervisorctl update

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

EXPOSE 80
EXPOSE 6001

COPY .docker/cmd.sh ./cmd.sh
RUN chmod +x ./cmd.sh
CMD ./cmd.sh