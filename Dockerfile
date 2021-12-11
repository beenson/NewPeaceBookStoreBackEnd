FROM laravelsail/php80-composer:latest
COPY . .
COPY .env.example .env
FROM composer
EXPOSE 8000
CMD ["php","artisan","serve"]