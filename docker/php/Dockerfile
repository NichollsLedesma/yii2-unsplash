FROM php:8-fpm-alpine
RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN apk update && apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libzip-dev libbz2 zlib-dev libxml2-dev openssl-dev libintl curl-dev icu-dev oniguruma-dev postgresql-dev php7-pdo_mysql libxml2-dev libmcrypt-dev g++ gcc libc-dev make libmcrypt-dev php7-bcmath php7-bz2 php7-calendar php7-exif php7-gettext php7-pcntl php7-pdo_mysql php7-shmop php7-sockets php7-sysvsem php7-sysvshm php7-tokenizer php7-xsl php7-opcache php7-pecl-xdebug gettext-dev libtool re2c libxslt-dev composer pkgconfig autoconf && \
  docker-php-ext-configure gd \
  --with-freetype --with-jpeg && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
docker-php-ext-install -j${NPROC} gd \
&& docker-php-ext-install bcmath \
&& docker-php-ext-install soap \
&& docker-php-ext-install xsl \
&& docker-php-ext-install opcache \
&& docker-php-ext-install pdo pgsql pdo_pgsql \
#&& docker-php-ext-install mysqli pdo pdo_mysql \
#&& docker-php-ext-enable pdo_mysql \
&& docker-php-ext-install sockets \
&& docker-php-ext-install shmop \
&& docker-php-ext-install sysvsem \
&& docker-php-ext-install sysvshm \
#&& docker-php-ext-install wddx \
&& docker-php-ext-install exif \
&& docker-php-ext-install intl \
&& docker-php-ext-install pcntl \
&& docker-php-ext-install gettext \
&& docker-php-ext-configure zip \
&& docker-php-ext-install zip

# Set timezone
RUN ln -snf /usr/share/zoneinfo/America/Argentina/Buenos_Aires /etc/localtime && echo America/Argentina/Buenos_Aires > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', America/Argentina/Buenos_Aires > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

RUN cd /tmp \
    ;wget https://xdebug.org/files/xdebug-3.0.3.tgz \
    ;tar xf xdebug-3.0.3.tgz \
    ;cd xdebug-3.0.3 \
    ;phpize \
    ;./configure \
    ;make \
    ;make install \
    ;sed -i "s/;//g" /etc/php7/conf.d/xdebug.ini \
    ;docker-php-ext-enable xdebug
    
#RUN echo "xdebug.remote_enable=on" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.remote_autostart=0" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.remote_port=9001" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.remote_handler=dbgp" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.remote_connect_back=1" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.var_display_max_data=3096" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.var_display_max_depth=5" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.remote_host=localhost" >> /etc/php7/conf.d/xdebug.ini \
#&&  echo "xdebug.remote_log=\"/tmp/xdebug.log\"" >> /etc/php7/conf.d/xdebug.ini
    

RUN pecl install mcrypt \
    ;docker-php-ext-enable mcrypt

RUN pecl install redis \
    ;docker-php-ext-enable redis

    
RUN sed -i "s/;listen.owner/listen.owner/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/;listen.group/listen.group/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" /usr/local/etc/php-fpm.d/www.conf && \
    #sed -i "s/pm.max_children = 5/pm.max_children = 200/g" /usr/local/etc/php-fpm.d/www.conf && \
    #sed -i "s/pm.start_servers = 2/pm.start_servers = 60/g" /usr/local/etc/php-fpm.d/www.conf && \
    #sed -i "s/pm.min_spare_servers = 1/pm.min_spare_servers = 20/g" /usr/local/etc/php-fpm.d/www.conf && \
    #sed -i "s/pm.max_spare_servers = 3/pm.max_spare_servers = 100/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/;pm.max_requests/pm.max_requests/g" /usr/local/etc/php-fpm.d/www.conf

RUN echo 'alias l="ls -CF"' >> ~/.bashrc
RUN echo 'alias la="ls -A"' >> ~/.bashrc
RUN echo 'alias ll="ls -alF"' >> ~/.bashrc
RUN echo 'alias ls="ls --color=auto"' >> ~/.bashrc

RUN echo 'alias l="ls -CF"' >> /home/www-data/.bashrc
RUN echo 'alias la="ls -A"' >> /home/www-data/.bashrc
RUN echo 'alias ll="ls -alF"' >> /home/www-data/.bashrc
RUN echo 'alias ls="ls --color=auto"' >> /home/www-data/.bashrc

EXPOSE 9000
USER www-data
