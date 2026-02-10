# Deployment Guide for HJParam Publication

This guide provides steps to deploy the HJParam Publication website to a production environment.

## 1. Server Requirements
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Nginx or Apache
- Composer
- Node.js & NPM (for building assets)

## 2. Preparation Steps

### Build Assets
Run the following command locally or on your building server:
```bash
npm install
npm run build
```

### Install PHP Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Configure Environment
Copy `.env.example` to `.env` and update the following:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://yourdomain.com`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Production credentials)
- `MAIL_HOST`, `MAIL_PORT`, etc. (SMTP credentials)

Generate a fresh application key:
```bash
php artisan key:generate
```

## 3. Deployment Script
You can use the following commands to finalize the deployment:

```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimized Caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run Migrations
php artisan migrate --force
```

## 4. File Permissions
Ensure the following directories are writable by the web server (usually `www-data`):
- `storage`
- `bootstrap/cache`

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 5. Security Checklist
- Ensure `.env` is NOT publicly accessible.
- Disable `APP_DEBUG` in production.
- Use HTTPS for all traffic.
- Regularly update `composer` and `npm` dependencies.

---
*Created by Antigravity AI*
