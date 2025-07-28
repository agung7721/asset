#!/bin/bash

echo "=== Persiapan Deployment ke Hostinger ==="

# 1. Clear cache dan optimize
echo "1. Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 2. Optimize aplikasi
echo "2. Optimizing application..."
php artisan optimize

# 3. Backup .env local
echo "3. Backing up local .env..."
cp .env .env.local

# 4. Copy konfigurasi Hostinger
echo "4. Setting up Hostinger configuration..."
cp .env.hostinger .env

# 5. Set permissions untuk deployment
echo "5. Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

# 6. Copy .htaccess untuk Hostinger
echo "6. Setting up .htaccess..."
cp .htaccess.hostinger public/.htaccess

echo ""
echo "=== Deployment siap! ==="
echo ""
echo "Langkah selanjutnya:"
echo "1. Upload semua file ke public_html/khz-asset/ di Hostinger"
echo "2. Import database khz_asset.dump ke phpMyAdmin"
echo "3. Set domain/subdomain point ke folder public/"
echo "4. Test aplikasi di https://khz.aulvan.com"
echo ""
echo "Login credentials:"
echo "- Email: admin@admin.com"
echo "- Password: password"
echo ""
echo "Untuk restore konfigurasi local:"
echo "cp .env.local .env" 