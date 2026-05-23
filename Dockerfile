FROM webdevops/php-nginx:8.2

WORKDIR /app

COPY . /app

RUN apt-get update && apt-get install -y nodejs npm

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

RUN php artisan config:clear
RUN php artisan cache:clear

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80