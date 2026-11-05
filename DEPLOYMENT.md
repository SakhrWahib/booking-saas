# Production deployment

A checklist for running Booking SaaS on a real server.

## Requirements
- PHP 8.3+ with `pdo_mysql`, `mbstring`, `zip`, `bcmath`, `intl`, `gd`
- MySQL 8 (the reports use MySQL date functions; SQLite is not supported)
- Node 20 (build step only), Composer 2
- `mysqldump` and `gzip` on the server (daily backups)

## 1. Environment (`.env`)
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
ASSET_URL=
LOG_LEVEL=warning

SESSION_SECURE_COOKIE=true
SESSION_DRIVER=database        # or redis
CACHE_DRIVER=redis             # or file
QUEUE_CONNECTION=database

TENANCY_CENTRAL_DOMAIN=your-domain.com   # each business opens at slug.your-domain.com
ADMIN_EMAIL=owner@your-domain.com        # first platform owner (seeding only)
ADMIN_PASSWORD=<strong password>
```
Never commit `.env`. `APP_DEBUG=true` in production exposes stack traces and secrets.

## 2. Install
```
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate          # first install only
php artisan migrate --force
php artisan db:seed --force       # first install only: roles, permissions, platform owner
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache
```
Re-run the cache commands after every deploy.

## 3. DNS and SSL
- `A` record for `your-domain.com` and a wildcard `*.your-domain.com` to the server.
- A wildcard certificate (e.g. Let's Encrypt DNS challenge) covers every business subdomain.
- A business with its own domain: set it in Platform → Businesses → domain, point its DNS to the server, and issue a certificate for it.

## 4. Scheduler and queue
```
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```
Runs the daily database backup at 03:00 (`backup:database --keep=14`, files in `storage/app/backups`).
Copy those files off the server too (S3, another machine): a backup on the same disk does not survive a disk failure.

Run a queue worker under Supervisor or systemd: `php artisan queue:work --tries=3`.

## 5. Monitoring
- `GET /up` returns `200 {"status":"ok"}` when the app and database are up, `503` otherwise. Point an uptime monitor at it.
- Logs: `storage/logs/laravel.log`.

## Security notes
- The admin and customer-data API (`/api/customers`, `/api/bookings`, write operations) needs a Sanctum token. Public catalog, availability and OTP endpoints are rate limited.
- Login: 5 attempts per minute. OTP: 5 per minute per IP, 20 per hour per phone.
- Security headers (`X-Frame-Options`, `nosniff`, `Referrer-Policy`, HSTS over HTTPS) are added to every response.
