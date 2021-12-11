FROM php:7.3
COPY . .
COPY .env.example .env
EXPOSE 8000
CMD ["php","artisan","serve"]