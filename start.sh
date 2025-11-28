#!/bin/bash

# Run migrations
php artisan migrate --force

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000
