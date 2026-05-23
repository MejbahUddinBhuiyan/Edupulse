FROM webdevops/php-nginx:8.2

WORKDIR /app

ENV WEB_DOCUMENT_ROOT=/app/public

COPY . /app

RUN apt-get update && apt-get install -y nodejs npm

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80