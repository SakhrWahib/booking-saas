#!/usr/bin/env bash
# يجهّز المشروع أول مرة داخل GitHub Codespaces
set -e

for l in public/storage storage/app/public; do if [ -L "$l" ]; then rm -f "$l"; fi; done
mkdir -p storage/app/public storage/framework/{cache,sessions,views} storage/logs bootstrap/cache

composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  cp .env.example .env
  sed -i 's/^DB_HOST=.*/DB_HOST=db/; s/^DB_DATABASE=.*/DB_DATABASE=booking/; s/^DB_USERNAME=.*/DB_USERNAME=booking/; s/^DB_PASSWORD=.*/DB_PASSWORD=booking/' .env
  php artisan key:generate
fi


echo "انتظار قاعدة البيانات..."
until mysqladmin ping -h db -u booking -pbooking --silent; do sleep 2; done

# كلمات مرور عشوائية لبيئة التطوير فقط، تُحفظ في ملف غير مرفوع على Git
CREDS=storage/app/dev-credentials.txt
if ! grep -q '^DEMO_ADMIN_PASSWORD=.\+' .env; then
  sed -i '/^DEMO_ADMIN_PASSWORD=/d; /^ADMIN_PASSWORD=/d; /^ADMIN_EMAIL=/d' .env
  echo "ADMIN_EMAIL=owner@mawidcloud.test" >> .env
  echo "ADMIN_PASSWORD=$(openssl rand -hex 6)" >> .env
  echo "DEMO_ADMIN_PASSWORD=$(openssl rand -hex 6)" >> .env
fi

php artisan migrate --seed --force
php artisan db:seed --class=DemoSeeder --force
php artisan storage:link || true

{
  echo "مالك المنصة: $(grep '^ADMIN_EMAIL=' .env | cut -d= -f2) / $(grep '^ADMIN_PASSWORD=' .env | cut -d= -f2)"
  echo "مدير النشاط التجريبي: demo@mawidcloud.test / $(grep '^DEMO_ADMIN_PASSWORD=' .env | cut -d= -f2)"
} > "$CREDS"
echo "بيانات الدخول في $CREDS"

npm install
npm run build

echo "✅ جاهز. الموقع يفتح على المنفذ 8000"
