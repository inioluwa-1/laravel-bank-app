#!/bin/bash

# Fresh migrations (drop all tables and recreate)
php artisan migrate:fresh --force

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000
