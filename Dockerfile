FROM php:8.4-cli-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring xml bcmath gd zip pcntl exif \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=node:20-bookworm /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20-bookworm /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY . .

RUN npm run build

EXPOSE 10000

CMD ["sh", "-c", "mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache; touch storage/logs/laravel.log; case \"${APP_KEY:-}\" in base64:*) ;; *) export APP_KEY=\"base64:$(php -r 'echo base64_encode(random_bytes(32));')\" ;; esac; php artisan package:discover --ansi; php artisan storage:link || true; php artisan migrate --force; php artisan db:seed --class=Database\\Seeders\\RolesAndUsersSeeder --force; php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
