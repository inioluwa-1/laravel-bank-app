#!/bin/bash

# Run migrations (without dropping tables)
php artisan migrate --force

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000
