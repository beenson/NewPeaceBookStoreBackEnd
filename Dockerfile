FROM php:7.3
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .
COPY .env.example .env
RUN composer install
RUN php artisan key:generate
RUN php artisan migrate
EXPOSE 8000
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]