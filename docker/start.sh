#!/usr/bin/env bash
set -euo pipefail

# Default PORT if not provided by environment (Render provides $PORT)
: "${PORT:=8080}"

# Render can inject domain; default to _
: "${SERVER_NAME:=_}"

echo "Starting container on port ${PORT}..."

# Generate Nginx site config from template with current PORT
envsubst '\n\r$PORT $SERVER_NAME' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Ensure directories exist and permissions are correct
mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Move to app root
cd /var/www/html

# Laravel optimizations and setup
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

php artisan package:discover --ansi || true
php artisan config:cache --ansi || true
php artisan route:cache --ansi || true
php artisan view:cache --ansi || true
php artisan event:cache --ansi || true

# Storage link if not exists
php artisan storage:link || true

# Run migrations (no interaction, ignore failure if no DB yet to allow container start)
php artisan migrate --force --no-interaction || echo "Migrations skipped/failed (database may be unavailable)."

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf


