FROM webdevops/php-nginx:8.2

WORKDIR /app

ENV WEB_DOCUMENT_ROOT=/app/public

COPY . /app

RUN apt-get update && apt-get install -y curl
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

RUN mkdir -p storage/logs
RUN touch storage/logs/laravel.log
RUN chown -R application:application /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 80