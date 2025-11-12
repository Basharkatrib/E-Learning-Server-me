#!/usr/bin/env sh
set -e

# Configure Nginx to Render's dynamic port
envsubst '${PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Laravel runtime prep
cd /app
php artisan storage:link || true
php artisan config:cache
# php artisan route:cache
php artisan view:cache
php artisan event:cache
# Uncomment if you want automatic schema updates on deploy
# php artisan migrate --force

exec /usr/bin/supervisord -c /etc/supervisord.conf


