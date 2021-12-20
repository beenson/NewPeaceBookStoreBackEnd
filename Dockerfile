FROM php:7.4
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .
COPY .env.example .env
RUN composer install
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN php artisan key:generate
RUN php artisan migrate
RUN php artisan jwt:secret
EXPOSE 8000
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]