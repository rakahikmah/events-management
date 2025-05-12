#!/bin/bash

# Set default values if not set
: "${PHP_MEMORY_LIMIT:=512M}"
: "${PHP_UPLOAD_MAX_FILESIZE:=100M}"

echo "Setting memory limit to ${PHP_MEMORY_LIMIT}"
echo "Setting upload max filesize to ${PHP_UPLOAD_MAX_FILESIZE}"

# Ubah konfigurasi PHP sesuai ENV
echo "memory_limit = ${PHP_MEMORY_LIMIT}" > /usr/local/etc/php/conf.d/99-custom.ini
echo "upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}" >> /usr/local/etc/php/conf.d/99-custom.ini
echo "post_max_size = ${PHP_UPLOAD_MAX_FILESIZE}" >> /usr/local/etc/php/conf.d/99-custom.ini

# Run the provided command
exec "$@"
