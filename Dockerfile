FROM webdevops/php-nginx:8.2

WORKDIR /app

ENV WEB_DOCUMENT_ROOT=/app/public

COPY . /app

# Install Node.js 20
RUN apt-get update && apt-get install -y curl

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

RUN apt-get install -y nodejs

# Verify node version
RUN node -v

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Install frontend deps
RUN npm install

# Build Vite assets
RUN npm run build

# Laravel permissions
RUN mkdir -p storage/logs
RUN touch storage/logs/laravel.log

RUN chown -R application:application /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 80