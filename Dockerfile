FROM php:7.2.18-apache

MAINTAINER Odenktools <odenktools86@gmail.com>

# https://github.com/docker-library/php/blob/master/7.2/stretch/apache/Dockerfile
# https://github.com/laradock/laradock/blob/master/php-fpm/Dockerfile

ENV TZ=Asia/Jakarta
ENV ODK_PHP=7.2.18
ENV ACCEPT_EULA=Y
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN echo $TZ > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo date.timezone = $TZ > /usr/local/etc/php/conf.d/docker-php-ext-timezone.ini

RUN apt-get update

RUN pecl channel-update pecl.php.net

RUN apt-get -y update \
    && apt-get install -y --no-install-recommends \
    build-essential g++ \
    nano \
    curl \
    wget \
    unzip \
    inetutils-ping \
    pkg-config \
    libz-dev \
    libjpeg-dev \
    libpng-dev \
    libgmp-dev \
    libfreetype6-dev \
    libxml2-dev \
    zlib1g-dev libicu-dev \
    apache2-utils \
    libcurl4-openssl-dev \
    libssl-dev \
    libkrb5-dev \
    libxslt1-dev \
    libmcrypt-dev \
    unixodbc-dev \
    mysql-client \
    libpq-dev \
    libreadline-dev \
    libfreetype6-dev \
    git

# Install cronjob
RUN apt-get -y update \
    && apt-get install -y --no-install-recommends \
    cron \
    dos2unix

# Install the PHP gd library
RUN docker-php-ext-install gd && \
    docker-php-ext-configure gd \
        --enable-gd-native-ttf \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd

RUN docker-php-ext-install pdo pdo_mysql \
    && pecl install mcrypt-1.0.1 \
    && pecl install xdebug \
    && docker-php-ext-install mbstring \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install zip \
    && docker-php-ext-install json \
    && docker-php-ext-install soap \
    && docker-php-ext-install xml \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install xsl \
    && docker-php-ext-install gmp \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-enable intl \
    && docker-php-ext-enable pdo \
    && docker-php-ext-enable gd \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-enable mbstring \
    && docker-php-ext-enable mysqli \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-enable pdo_pgsql \
    && docker-php-ext-enable pgsql \
    && docker-php-ext-enable zip \
    && docker-php-ext-enable soap \
    && docker-php-ext-enable json \
    && docker-php-ext-enable xml \
    && docker-php-ext-enable xsl \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install sockets \
    && docker-php-ext-enable bcmath 

## ======================================== INSTALLING WKHTMLTOPDF ========================================
ARG INSTALL_WKHTMLTOPDF=0
RUN if [ ${INSTALL_WKHTMLTOPDF} = 1 ]; then \
    apt-get update && \
      apt-get install -yqq \
      libxrender1 \
      libfontconfig1 \
      libx11-dev \
      libjpeg62 \
      libxtst6 \
      fontconfig \
      libjpeg62-turbo \
      xfonts-base \
      xfonts-75dpi \
    && wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.stretch_amd64.deb \
    && dpkg -i wkhtmltox_0.12.6-1.stretch_amd64.deb \
    && apt -f install \
;fi

## ======================================== INSTALLING IMAGEMAGICK ========================================

ARG INSTALL_IMAGEMAGICK=0
ARG IMAGEMAGICK_VERSION=latest
ENV IMAGEMAGICK_VERSION ${IMAGEMAGICK_VERSION}

RUN if [ ${INSTALL_IMAGEMAGICK} = 1 ]; then \
    apt-get update && \
    apt-get install -yqq libmagickwand-dev imagemagick && \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
      cd /tmp && \
      if [ ${IMAGEMAGICK_VERSION} = "latest" ]; then \
        git clone https://github.com/Imagick/imagick; \
      else \
        git clone --branch ${IMAGEMAGICK_VERSION} https://github.com/Imagick/imagick; \
      fi && \
      cd imagick && \
      phpize && \
      ./configure && \
      make && \
      make install && \
      rm -r /tmp/imagick; \
    else \
      pecl install imagick; \
    fi && \
    docker-php-ext-enable imagick; \
    php -m | grep -q 'imagick' \
;fi

ARG INSTALL_PHPREDIS=0
RUN if [ ${INSTALL_PHPREDIS} = 1 ]; then \
    # Install Php Redis Extension
apt-get update && \
    pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
;fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# CLEAN PACKAGES
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN mkdir -p /var/www/html && mkdir -p /etc/apache2/ssl

# EXPOSE APACHE LARAVEL PUBLIC PATH
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY docker-conf/php/php.ini /usr/local/etc/php/php.ini

# ADD CRONJOBS
ADD docker-conf/cron/app-cronjobs.sh /etc/cron.d/app-cronjobs.sh
## convert to unix format.
RUN dos2unix /etc/cron.d/app-cronjobs.sh
RUN chmod 0644 /etc/cron.d/app-cronjobs.sh
RUN crontab /etc/cron.d/app-cronjobs.sh
RUN touch /var/log/cron.log
RUN cron

# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN chown -R www-data:www-data /var/www/

RUN chmod -R 777 /var/www/

RUN a2enmod rewrite proxy expires headers && service apache2 restart

VOLUME ["/var/www/html", "/etc/apache2/sites-available"]

WORKDIR /var/www/html

COPY docker-conf/entrypoint.sh /entrypoint

RUN dos2unix /entrypoint

RUN chmod +x /entrypoint

COPY docker-conf/boot.sh /usr/local/bin/boot-app

RUN dos2unix /usr/local/bin/boot-app

RUN chmod +x /usr/local/bin/boot-app

COPY docker-conf/wait-for-it.sh /usr/local/bin/wait-for-it

RUN dos2unix /usr/local/bin/wait-for-it

RUN chmod +x /usr/local/bin/wait-for-it

ENTRYPOINT ["/entrypoint"]

CMD ["/usr/local/bin/boot-app"]

EXPOSE 80 9001

#eof