#!/bin/bash

set -e

cd "$(dirname "$0")"

echo "==> Mengecek dependencies..."

if [ ! -d "vendor" ]; then
    echo "==> Menjalankan composer install..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "==> Menjalankan npm install..."
    npm install
fi

if [ ! -f ".env" ]; then
    echo "==> Menyalin .env.example ke .env..."
    cp .env.example .env
    php artisan key:generate
fi

echo "==> Menjalankan migrasi database..."
php artisan migrate --force

echo "==> Membersihkan cache..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "==> Menjalankan aplikasi..."
npx concurrently -c "#93c5fd,#c4b5fd,#fdba74" \
    "php artisan serve" \
    "php artisan queue:listen --tries=1 --timeout=0" \
    "npm run dev" \
    --names=server,queue,vite --kill-others
