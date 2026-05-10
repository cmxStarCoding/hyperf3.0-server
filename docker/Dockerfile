FROM php:8.2-fpm-bookworm

RUN sed -i 's/deb.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list.d/debian.sources

RUN apt-get update && apt-get install -y \
    supervisor \
    unzip \
    libssl-dev \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    libsqlite3-dev \
    libmagickwand-dev \
    libcurl4-openssl-dev \
    pkg-config \
    bash-completion \
    librabbitmq-dev \
    psmisc \
    procps \
    vim \
    lsof \
    bash \
    cron \
    git \
    autoconf \
    bison \
    re2c \
    libtool \
    make \
    gcc \
    libc-dev \
    libpcre2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libnghttp2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

RUN pecl channel-update pecl.php.net

RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pdo_sqlite

RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN pecl install yac \
    && docker-php-ext-enable yac

RUN pecl install psr-1.1.0 \
    && docker-php-ext-enable psr

RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install gettext
RUN docker-php-ext-install mbstring

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install gd

RUN docker-php-ext-install sockets

RUN printf "yes\nyes\nyes\nno\nno\nno\nno\nno\n" | pecl install openswoole-22.1.2 \
    && docker-php-ext-enable openswoole \
    && mv /usr/local/etc/php/conf.d/docker-php-ext-openswoole.ini \
              /usr/local/etc/php/conf.d/zz-docker-php-ext-openswoole.ini

RUN echo "openswoole.use_shortname = Off" >> /usr/local/etc/php/conf.d/zz-docker-php-ext-openswoole.ini

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install amqp-1.11.0 \
    && docker-php-ext-enable amqp

RUN pecl install protobuf-3.21.12 \
    && docker-php-ext-enable protobuf

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y unzip && \
    curl -LO https://github.com/protocolbuffers/protobuf/releases/download/v21.11/protoc-21.11-linux-x86_64.zip && \
    unzip protoc-21.11-linux-x86_64.zip -d /usr/local && \
    rm protoc-21.11-linux-x86_64.zip && \
    chmod +x /usr/local/bin/protoc && \
    rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

RUN mkdir -p /etc/supervisor/conf.d \
    && mkdir -p /var/log/supervisor

WORKDIR /var/www

RUN mkdir -p /var/www/storage/logs \
    && chmod -R 777 /var/www/storage

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]