#!/usr/bin/env bash
set -e

cd /var/www

rsync -a --delete "/src/${SERVICE_PATH}/" ./

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

until php artisan migrate:fresh --seed --force; do
  echo "Waiting for DB at $DB_HOST:$DB_PORT..."
  sleep 3
done

# run application tests
echo "→ Running PHPUnit tests…"
php artisan test

if [ "$SERVICE_PATH" = "web-client" ]; then
  echo "→ Installing JS dependencies & building assets for web-client…"
  npm install
  npm run build
fi

php artisan serve --host=0.0.0.0 --port=${APP_PORT}
