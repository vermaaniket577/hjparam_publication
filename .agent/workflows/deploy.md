---
description: How to prepare the application for production deployment
---

This workflow prepares the HJParam Publication website for a production environment.

// turbo-all
1. Build production assets
```bash
npm run build
```

2. Clear application caches
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

3. Optimize application for production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. Verify current status
```bash
php artisan about
```
