FROM ubuntu:18.04
RUN apt update
FROM php:7.3
RUN apt-get -y install composer
RUN composer global require laravel/envoy
RUN chmod +x ~/.config/composer/vendor/bin/envoy
RUN ln -s ~/.config/composer/vendor/bin/envoy /usr/local/bin/envoy
COPY . .
COPY .env.example .env
RUN composer install
EXPOSE 8000
CMD ["php","artisan","serve"]