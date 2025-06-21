#!/usr/bin/env bash
set -e

cd /var/www
#rsync -a --delete "/src/${SERVICE_PATH}/" ./

if [ -f composer.json ]; then
  echo "→ Installing PHP dependencies with Composer…"
  composer install --no-interaction --optimize-autoloader
fi

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

if [ "$SERVICE_PATH" = "auth-service" ]; then
   echo "→ Generating JWT secrets and certificates…"
   php artisan jwt:secret --force
   php artisan jwt:generate-certs --force
fi

#if [ "$SERVICE_PATH" = "web-client-service" ] && [ -f package.json ]; then
#  echo "→ Cleaning JS workspace…"
#  rm -rf node_modules
#
#  echo "→ Installing JS dependencies for web-client…"
#  npm install --no-optional || echo "⚠️ npm install failed, continuing..."
#
#  echo "→ Building frontend…"
#  npm run build || echo "⚠️ npm build failed, continuing..."
#fi

if [ "$SERVICE_PATH" != "web-client-service" ] && [ -n "$DB_HOST" ]; then
  until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" --silent; do
    echo "Waiting for DB at $DB_HOST:$DB_PORT..."
    sleep 3
  done

  until php artisan migrate:fresh --seed --force; do
      echo "Waiting for DB at $DB_HOST:$DB_PORT..."
      sleep 3
    done
fi

echo "→ Run the composer dump autoload..."
composer dump-autoload

# run application tests
echo "→ Running PHPUnit tests…"
php artisan test

php artisan serve --host=0.0.0.0 --port=${APP_PORT}
