#!/usr/bin/env bash
set -e

until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" --silent; do
  echo "Waiting for DB at $DB_HOST:$DB_PORT..."
  sleep 3
done

cd /var/www

rsync -a --delete "/src/${SERVICE_PATH}/" ./

if [ -f composer.json ]; then
  echo "→ Installing PHP dependencies with Composer…"
  composer install --no-interaction --optimize-autoloader
fi

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
   if [ "$SERVICE_PATH" = "auth-service" ]; then
      echo "→ Generating JWT secrets and certificates…"
      php artisan jwt:secret
      php artisan jwt:generate-certs
  fi
fi

until php artisan migrate:fresh --seed --force; do
  echo "Waiting for DB at $DB_HOST:$DB_PORT..."
  sleep 3
done

if [ -f package.json ]; then
  echo "→ Installing JS dependencies…"
  npm install
  npm run build
fi

# run application tests
echo "→ Running PHPUnit tests…"
php artisan test

php artisan serve --host=0.0.0.0 --port=${APP_PORT}
