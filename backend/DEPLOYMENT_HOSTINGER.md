# JEFAL Prive - Laravel Backend (Hostinger Ready)

## 1) Prerequisites
- PHP 8.2+
- MySQL 8+
- Composer 2+
- Apache with `mod_rewrite`

## 2) Local setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
```

## 3) Mail configuration
Update `.env`:
- `MAIL_HOST=smtp.hostinger.com`
- `MAIL_PORT=587`
- `MAIL_USERNAME=...`
- `MAIL_PASSWORD=...`
- `MAIL_ENCRYPTION=tls`

## 4) CMI placeholders
Set CMI env vars:
- `CMI_MERCHANT_ID`
- `CMI_TERMINAL_ID`
- `CMI_SECRET`
- `CMI_BASE_URL`

## 5) Shared hosting deployment (Hostinger)
1. Upload backend project files outside `public_html` (for example `~/backend`).
2. Copy contents of `/public` into `public_html`.
3. Update `public_html/index.php` paths to point to `../backend/bootstrap/app.php` and autoload.
4. Place root `.htaccess` in `public_html`.
5. Set permissions:
   - `storage/` writable
   - `bootstrap/cache/` writable
6. Configure production `.env` (`APP_ENV=production`, `APP_DEBUG=false`).
7. Run once:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6) Cron jobs
Add Hostinger cron entries:
```bash
* * * * * php /home/USERNAME/backend/artisan schedule:run >> /dev/null 2>&1
```

## 7) Security checklist
- Force HTTPS in hosting panel
- Strong DB credentials
- Unique `APP_KEY`
- Disable debug in production
- Regular backups for DB + storage
