#!/bin/bash

mkdir -p /var/www/html/writable/cache
chown -R www-data:www-data /var/www/html/writable
chmod -R 775 /var/www/html/writable

# Execute CMD
exec "$@"
