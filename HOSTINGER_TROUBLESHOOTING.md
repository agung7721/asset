# Troubleshooting Aplikasi di Hostinger

## Error 500 Internal Server Error

### 1. Cek Error Log
```bash
# Di cPanel, buka Error Log
# Cari error terkait Laravel
```

### 2. Periksa File .env
- Pastikan file `.env` ada di root folder
- Pastikan konfigurasi database benar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u559156733_khzdev
DB_USERNAME=u559156733_khzdev
DB_PASSWORD=Kurhanz123%
```

### 3. Periksa Permissions
```bash
chmod 644 .env
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 4. Periksa Database
- Buka phpMyAdmin
- Pastikan database `u559156733_khzdev` ada
- Pastikan tabel-tabel Laravel sudah terimport

## Database Connection Error

### 1. Test Koneksi Database
```bash
# Di cPanel Terminal atau SSH
mysql -u u559156733_khzdev -p'Kurhanz123%' -h 127.0.0.1
```

### 2. Periksa Database Credentials
- Username: `u559156733_khzdev`
- Password: `Kurhanz123%`
- Database: `u559156733_khzdev`
- Host: `127.0.0.1`

### 3. Import Database
```sql
-- Di phpMyAdmin
-- Import file khz_asset.dump
```

## File Not Found Error

### 1. Periksa Struktur Folder
```
public_html/
└── khz-asset/
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/          # <- Domain harus point ke sini
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    └── artisan
```

### 2. Periksa .htaccess
- File `.htaccess` harus ada di folder `public/`
- Pastikan mod_rewrite aktif di Hostinger

### 3. Periksa Domain Configuration
- Di cPanel, buka "Domains"
- Pastikan domain point ke folder `public_html/khz-asset/public/`

## White Screen Error

### 1. Enable Debug Mode
```env
APP_DEBUG=true
APP_ENV=local
```

### 2. Cek PHP Version
- Pastikan PHP version 8.0+ di Hostinger
- Cek di cPanel > PHP Configuration

### 3. Cek Extensions
Pastikan extension berikut aktif:
- pdo_mysql
- mbstring
- openssl
- tokenizer
- xml
- ctype
- json

## Slow Loading

### 1. Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Enable OPcache
- Di cPanel, aktifkan OPcache
- Set memory_limit sesuai kebutuhan

### 3. Compress Assets
- Gunakan gzip compression
- Minify CSS dan JS

## Login Issues

### 1. Reset Password Admin
```bash
# Di terminal Hostinger
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'admin@admin.com')->first();
$user->password = Hash::make('password');
$user->save();
```

### 2. Clear Session
```bash
php artisan session:table
php artisan migrate
```

## Common Commands untuk Hostinger

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize
php artisan optimize

# Check status
php artisan about

# List routes
php artisan route:list

# Check database
php artisan tinker
DB::connection()->getPdo();
```

## Contact Support

Jika masih bermasalah:
1. Screenshot error message
2. Copy error log dari cPanel
3. Pastikan semua langkah di atas sudah dilakukan
4. Hubungi support Hostinger dengan detail error 