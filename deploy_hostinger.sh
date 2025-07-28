#!/bin/bash

echo "=== Deploy KHZ Asset Management ke Hostinger ==="

# 1. Clear Laravel Cache
echo "1. Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 2. Optimize Laravel
echo "2. Optimizing Laravel..."
php artisan optimize

# 3. Set proper permissions
echo "3. Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

# 4. Create symbolic links if needed
echo "4. Creating storage link..."
php artisan storage:link

# 5. Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "5. Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
else
    echo "5. Updating Composer dependencies..."
    composer update --no-dev --optimize-autoloader
fi

# 6. Check if node_modules exists and build assets
if [ -f "package.json" ]; then
    if [ ! -d "node_modules" ]; then
        echo "6. Installing NPM dependencies..."
        npm install
    fi
    
    echo "7. Building assets..."
    npm run build
fi

# 7. Check database connection
echo "8. Testing database connection..."
php artisan tinker --execute="echo 'Database connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED') . PHP_EOL;"

# 8. Run migrations if needed
echo "9. Running migrations..."
php artisan migrate --force

# 9. Clear all caches again
echo "10. Final cache clearing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Deployment completed! ==="
echo "Please check your application at: https://khz.aulvan.com"
echo ""
echo "If you see issues:"
echo "1. Check error logs in cPanel"
echo "2. Verify .env configuration"
echo "3. Ensure database credentials are correct"
echo "4. Check file permissions" 