#!/usr/bin/env bash
# يجهّز المشروع أول مرة داخل GitHub Codespaces
set -e


composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  cp .env.example .env
  sed -i 's/^DB_HOST=.*/DB_HOST=db/; s/^DB_DATABASE=.*/DB_DATABASE=booking/; s/^DB_USERNAME=.*/DB_USERNAME=booking/; s/^DB_PASSWORD=.*/DB_PASSWORD=booking/' .env
  php artisan key:generate
fi

mkdir -p storage/app/public storage/framework/{cache,sessions,views} storage/logs bootstrap/cache

echo "انتظار قاعدة البيانات..."
until mysqladmin ping -h db -u booking -pbooking --silent; do sleep 2; done

php artisan migrate --seed --force
php artisan storage:link || true

npm install
npm run build

echo "✅ جاهز. الموقع يفتح على المنفذ 8000"
