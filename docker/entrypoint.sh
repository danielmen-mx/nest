#!/bin/bash

echo "⏳ Esperando DB..."
until nc -z db 3306; do
  sleep 1
done

cd /var/www

echo "📦 Instalando dependencias..."
composer install --no-interaction --prefer-dist

echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear

echo "🔑 Verificando APP_KEY..."
if ! grep -q "APP_KEY=base64" .env; then
  echo "⚡ Generando APP_KEY..."
  php artisan key:generate --force
else
  echo "✅ APP_KEY ya existe"
fi

echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo "🔐 Verificando Passport..."
if [ ! -f storage/oauth-private.key ]; then
  echo "⚡ Instalando Passport..."
  php artisan passport:install
else
  echo "✅ Passport ya instalado"
fi

echo "🚀 Iniciando PHP-FPM..."
php-fpm