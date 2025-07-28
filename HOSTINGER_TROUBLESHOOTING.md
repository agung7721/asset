# Troubleshooting Aplikasi di Hostinger

## Masalah Tampilan Login Page

### 1. Logo Tidak Tampil
**Gejala:** Logo Kurhanz Trans tidak muncul, hanya teks
**Solusi:**
```bash
# Pastikan file logo ada di folder public/images/
ls -la public/images/logo-kurhanz.png

# Jika tidak ada, upload file logo ke folder tersebut
# Pastikan path di login.blade.php menggunakan asset() helper
<img src="{{ asset('images/logo-kurhanz.png') }}" alt="Kurhanz Trans Logo">
```

### 2. CSS AdminLTE Tidak Dimuat
**Gejala:** Tampilan terlihat plain tanpa styling
**Solusi:**
```bash
# Pastikan folder vendor/adminlte ada
ls -la public/vendor/adminlte/

# Jika tidak ada, install AdminLTE
composer require jeroennoten/laravel-adminlte

# Publish assets AdminLTE
php artisan adminlte:install

# Clear cache
php artisan config:clear
php artisan view:clear
```

### 3. Asset Path Tidak Benar
**Gejala:** CSS/JS tidak dimuat, console error 404
**Solusi:**
```bash
# Periksa .htaccess di folder public/
cat public/.htaccess

# Pastikan mod_rewrite aktif di Hostinger
# Di cPanel > Apache Configuration > Enable mod_rewrite

# Test asset URL
curl -I https://khz.aulvan.com/vendor/adminlte/dist/css/adminlte.css
```

### 4. Permission File
**Gejala:** File tidak dapat diakses
**Solusi:**
```bash
# Set permission yang benar
chmod -R 755 public/
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

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

## Langkah-langkah Perbaikan Tampilan

### 1. Upload File yang Diperbaiki
```bash
# Upload file yang sudah diperbaiki:
# - resources/views/auth/login.blade.php
# - public/.htaccess
# - deploy_hostinger.sh
```

### 2. Jalankan Script Deployment
```bash
# Di terminal Hostinger
chmod +x deploy_hostinger.sh
./deploy_hostinger.sh
```

### 3. Periksa Asset
```bash
# Pastikan semua asset AdminLTE ada
ls -la public/vendor/adminlte/dist/css/
ls -la public/vendor/adminlte/dist/js/
ls -la public/vendor/adminlte/dist/img/
```

### 4. Test Tampilan
- Buka https://khz.aulvan.com/login
- Periksa browser console untuk error
- Pastikan logo tampil dengan benar
- Pastikan styling AdminLTE dimuat

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