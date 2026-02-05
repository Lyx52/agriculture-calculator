FROM php:8.3.22-fpm-bullseye

ARG COMPOSER_VERSION=2.8.6
ENV NODE_VERSION=20.20.0
ARG CRON_LOG_DIRECTORY=/var/log/cron
ARG PROJECT_DIRECTORY=/srv/www

WORKDIR /srv/www

# Add Forticlient certificate
COPY ./docker/certs/Fortinet_CA_SSL.cer /usr/share/ca-certificates/
RUN grep -qxF 'Fortinet_CA_SSL.cer' /etc/ca-certificates.conf || echo 'Fortinet_CA_SSL.cer' >> /etc/ca-certificates.conf && \
    update-ca-certificates

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
    autoconf  \
    libtool  \
    build-essential \
    nginx

# Install Mailpit
RUN curl -L https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64 \
    -o /usr/local/bin/mailpit && \
    chmod +x /usr/local/bin/mailpit


# Install PHP Extensions:
# Only compile those that require custom build (e.g., gd, ldap, soap)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install bcmath curl gd ldap soap zip pdo pdo_mysql intl exif pcntl


# Enable optional extensions (if needed)
RUN docker-php-ext-enable ldap

# Install Redis & Xdebug
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --version=${COMPOSER_VERSION} --filename=composer

# Create app directory
RUN mkdir -p ${PROJECT_DIRECTORY} && mkdir -p ${CRON_LOG_DIRECTORY} && chown -R www-data:www-data ${PROJECT_DIRECTORY}

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

# Create app directory
COPY --chown=www-data:www-data ./project ${PROJECT_DIRECTORY}/

# Copy storage files (Repository persistent)
COPY --chown=www-data:www-data ./project/storage ${PROJECT_DIRECTORY}/repo_storage

RUN composer install
RUN composer dump-autoload

RUN npm install
RUN npm run build

RUN rm -f ${PROJECT_DIRECTORY}/public/hot

# Add cron
RUN crontab -l -u www-data | { cat; echo "* * * * * cd /srv/www/ && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1"; } | crontab -u www-data -

# Copy Nginx Configurations
COPY ./docker/nginx/conf.d/vhost.conf /etc/nginx/conf.d/vhost.conf
RUN rm /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copy other configs & scripts
COPY ./docker/start-container /usr/local/bin/start-container
RUN dos2unix /usr/local/bin/start-container
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
RUN chmod +x /usr/local/bin/start-container
COPY ./docker/patches/Add_force_https.patch /srv/www/Add_force_https.patch

EXPOSE 80 9000

ENTRYPOINT ["start-container"]