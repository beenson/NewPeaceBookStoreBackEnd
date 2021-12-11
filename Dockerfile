FROM ubuntu:18.04
RUN add-apt-repository ppa:ondrej/php && apt-get update && apt-get -y install php7.3-fpm php7.3-common php7.3-mysql php7.3-xml php7.3-xmlrpc php7.3-curl php7.3-gd php7.3-imagick php7.3-cli php7.3-dev php7.3-imap php7.3-mbstring php7.3-opcache php7.3-soap php7.3-zip php7.3-intl
RUN apt-get install composer
RUN composer global require laravel/envoy
RUN chmod +x ~/.config/composer/vendor/bin/envoy
RUN ln -s ~/.config/composer/vendor/bin/envoy /usr/local/bin/envoy
COPY . .
COPY .env.example .env
RUN composer install
EXPOSE 8000
CMD ["php","artisan","serve"]