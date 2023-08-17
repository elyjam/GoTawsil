#!/bin/bash
# entrypoint.sh

# Create missing directories

mkdir -p storage/framework
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p database/seeds
mkdir -p database/factories

chown -R www-data:www-data storage
chown -R www-data:www-data database

# Install dependencies
composer update

php artisan optimize

php artisan migrate

php artisan route:clear

# Continue with Apache in the foreground
apache2-foreground