FROM php:8.3.22-fpm-bullseye

ARG COMPOSER_VERSION=2.8.6
ENV NODE_VERSION=20.20.0

WORKDIR /srv/www

# Install dependencies
RUN apt-get update && apt-get -y install \
    libxml2-dev  \
    libmcrypt-dev  \
    dos2unix  \
    nano  \
    zip  \
    unzip  \
    wget  \
    cron  \
    supervisor  \
    git  \
    curl \
    libcurl4-openssl-dev  \
    libldap2-dev  \
    libsodium-dev  \
    libzip-dev  \
    libpng-dev  \
    libjpeg-dev \
    libfreetype6-dev  \
    libonig-dev  \
    libxslt1-dev  \
    libssl-dev \
    pkg-config  \
    libpq-dev \
    libicu-dev \
    autoconf  \
    libtool  \
    build-essential \
    nginx

# Install Mailpit
RUN curl -L https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64 \
    -o /usr/local/bin/mailpit && \
    chmod +x /usr/local/bin/mailpit

# Install PHP Extensions:
RUN docker-php-ext-install -j$(nproc) \
    gd \
    pcntl \
    curl \
    exif \
    gd \
    pdo_mysql \
    opcache \
    intl \
    zip \
    bcmath \
    soap

# Install Redis & Xdebug
RUN pecl install redis && pecl install xdebug && docker-php-ext-enable redis xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --version=${COMPOSER_VERSION} --filename=composer

# Install Nodejs
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
RUN . ~/.bashrc
ENV NVM_DIR=/root/.nvm
ENV NVM_NODEJS_ORG_MIRROR=http://nodejs.org/dist
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm config set strict-ssl false
RUN npm install -g yarn
RUN corepack enable && \
        corepack prepare yarn@4.7.0 --activate


# Add cron
# RUN crontab -l -u www-data | { cat; echo "* * * * * cd /srv/www/ && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1"; } | crontab -u www-data -


# Copy Nginx Configurations
COPY ./docker/nginx/conf.d/vhost.conf /etc/nginx/conf.d/vhost.conf
RUN rm /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copy other configs & scripts
COPY ./docker/start-container-development /usr/local/bin/start-container
RUN dos2unix /usr/local/bin/start-container
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN chmod +x /usr/local/bin/start-container

COPY ./project /srv/www



EXPOSE 80 9000

ENTRYPOINT ["start-container"]
