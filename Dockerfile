FROM ubuntu:18.04
RUN apt-get update -y && apt-get install -y openssl zip unzip git
FROM php:7.3
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer global require laravel/envoy
RUN chmod +x ~/.config/composer/vendor/bin/envoy
RUN ln -s ~/.config/composer/vendor/bin/envoy /usr/local/bin/envoy
COPY . .
COPY .env.example .env
RUN composer install
EXPOSE 8000
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8080"]