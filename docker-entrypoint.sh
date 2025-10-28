#!/bin/bash
set -e

# ATTENTE DE LA BD (si DB_HOST fourni)
if [ -n "$DB_HOST" ]; then
  echo "Waiting for database at $DB_HOST:$DB_PORT ..."
  MAX_ATTEMPTS=30
  ATTEMPT=0
  until nc -z $DB_HOST $DB_PORT; do
    ATTEMPT=$((ATTEMPT+1))
    if [ $ATTEMPT -ge $MAX_ATTEMPTS ]; then
      echo "Database not available after $MAX_ATTEMPTS attempts, continuing..."
      break
    fi
    sleep 1
  done
fi

# CREER .env SI ABSENT (Render injecte les env vars)
if [ ! -f .env ]; then
  echo ".env not found, creating from environment variables..."
  cat > .env <<EOF
APP_NAME=${APP_NAME:-Avatar}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost:8000}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-forge}
DB_USERNAME=${DB_USERNAME:-forge}
DB_PASSWORD=${DB_PASSWORD:-}

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
else
  echo ".env found, skipping creation."
fi

# Clear config cache and run migrations/seeds (safe)
php artisan config:clear || true
php artisan migrate --force || true
php artisan db:seed --class=SvgFinalElementsSeeder || true

# Link storage if needed
php artisan storage:link || true

# Execute main process
exec "$@"
