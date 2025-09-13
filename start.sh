#!/bin/bash

# Exit on any error
set -e

echo "Starting Laravel application setup..."

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "Caching configuration..."
php artisan config:clear
php artisan config:cache

# Clear and cache routes
echo "Caching routes..."
php artisan route:clear
php artisan route:cache

# Clear and cache views
echo "Caching views..."
php artisan view:clear
php artisan view:cache

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Start Apache
echo "Starting Apache server..."
apache2-foreground
