#!/bin/sh

# Default environment variables if not set
DB_HOST="${MYSQLHOST:-${MYSQL_HOST:-localhost}}"
DB_PORT="${MYSQLPORT:-${MYSQL_PORT:-3306}}"
DB_USER="${MYSQLUSER:-${MYSQL_USER:-root}}"
DB_PASS="${MYSQLPASSWORD:-${MYSQL_PASSWORD:-}}"
DB_NAME="${MYSQLDATABASE:-${MYSQL_DATABASE:-railway}}"

echo "Importing schema via PHP..."
php /var/www/html/database/seed.php

echo "Starting php-fpm..."
php-fpm -D

echo "Starting nginx..."
nginx -g "daemon off;"
