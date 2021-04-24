FROM php:8.0.3-fpm
# Set working directory
WORKDIR /var/www/html
# Install dependencies
RUN apt-get update && apt-get install --no-install-recommends -y \
        g++ \
        pkg-config \
        libssl-dev \
        libzip-dev \
        libpq-dev \
        libonig-dev \
        unzip \
        curl \
        wget
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl
# Redis
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html
# Change current user to www
USER www
EXPOSE 9000
CMD ["php-fpm"]