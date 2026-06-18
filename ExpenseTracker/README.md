# ExpenseTracker

A simple expense tracking web app built with Laravel.

## Requirements
- PHP 8.1 or higher
- Composer
- Node.js (for building CSS assets)

## How to run

1. Clone the repo and go into the folder
2. Run: composer install
3. Run: npm install && npm run build
4. Copy .env.example to .env and run: php artisan key:generate
5. Create the database: touch database/database.sqlite
6. Run migrations and seed demo data: php artisan migrate --seed
7. Link storage: php artisan storage:link
8. Start the server: php artisan serve

Open http://127.0.0.1:8000 in your browser.

## Demo login
Email: demo@example.com
Password: password

## Features
- Register and login
- Add, edit, delete expenses with optional receipt upload
- Filter expenses by month, year, and category
- Set up recurring expenses that auto-log each month
- Budget coach shows spending vs limits per category
- Monthly reports with charts

## Run the recurring expense command manually
php artisan expenses:log-recurring
