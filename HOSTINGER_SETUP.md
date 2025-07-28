# Setup Aplikasi KHZ Asset Management di Hostinger

## 1. Upload File ke Hostinger
```bash
# Upload semua file ke public_html/khz-asset/
# Pastikan struktur folder seperti ini:
public_html/
└── khz-asset/
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    └── artisan
```

## 2. Konfigurasi .env untuk Hostinger
```env
APP_NAME="KHZ Asset Management"
APP_ENV=production
APP_KEY=base64:OXs+HJJX3ZowGKRN9F9tWTCQ5n59vR59rsEDpnJBddM=
APP_DEBUG=false
APP_URL=https://khz.aulvan.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u559156733_khzdev
DB_USERNAME=u559156733_khzdev
DB_PASSWORD=Kurhanz123%

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## 3. Setup Database di Hostinger
1. Buka cPanel Hostinger
2. Masuk ke phpMyAdmin
3. Buat database baru: `u559156733_khzdev`
4. Import file `khz_asset.dump` ke database tersebut

## 4. Set Permissions
```bash
# Set permission untuk storage dan bootstrap/cache
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## 5. Setup Domain/Subdomain
1. Buat subdomain `khz.aulvan.com` di cPanel
2. Point ke folder `public_html/khz-asset/public/`
3. Atau gunakan domain utama dan point ke folder tersebut

## 6. Konfigurasi .htaccess
Buat file `.htaccess` di root domain dengan isi:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ khz-asset/public/$1 [L]
</IfModule>
```

## 7. Clear Cache dan Optimize
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
```

## 8. Test Aplikasi
- Buka https://khz.aulvan.com
- Login dengan:
  - Email: admin@admin.com
  - Password: password

## Troubleshooting

### Error 500 Internal Server Error
1. Cek error log di cPanel
2. Pastikan .env file ada dan benar
3. Pastikan database credentials benar
4. Cek permission folder storage dan bootstrap/cache

### Database Connection Error
1. Pastikan database name benar: `u559156733_khzdev`
2. Pastikan username benar: `u559156733_khzdev`
3. Pastikan password benar: `Kurhanz123%`
4. Pastikan host: `127.0.0.1`

### File Not Found Error
1. Pastikan struktur folder benar
2. Pastikan .htaccess file ada
3. Pastikan domain point ke folder public/ 