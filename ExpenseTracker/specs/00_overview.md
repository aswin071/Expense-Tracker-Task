# ExpenseTracker — Project Overview

## App Name
ExpenseTracker

## Stack
- **Framework:** Laravel 10
- **Database:** SQLite (self-contained, no external DB server)
- **Templating:** Blade
- **Styling:** Tailwind CSS (bundled with Laravel Breeze)
- **Authentication:** Laravel Breeze
- **ORM:** Eloquent (no raw SQL)
- **File Storage:** Laravel Storage facade (local/public disk)
- **Scheduler:** Laravel built-in Scheduler (Kernel.php)
- **Validation:** Laravel Form Request classes

## Purpose
A daily personal expense tracker with the following capabilities:
- Log and categorize daily expenses
- Attach receipt images or PDFs to individual expenses
- Define recurring expenses that auto-log on a set day each month
- Show pre-spend awareness before submitting a new expense
- Generate monthly and all-time category-based spending reports

## Constraints
- Entirely self-contained — no external APIs, no third-party services
- SQLite only — single `.sqlite` file for the database
- All queries must be scoped to the authenticated user

## Laravel Directory Conventions
| Artifact | Path |
|---|---|
| Controllers | `app/Http/Controllers/` |
| Models | `app/Models/` |
| Views | `resources/views/` |
| Routes | `routes/web.php` |
| Migrations | `database/migrations/` |
| Seeders | `database/seeders/` |
| Artisan Commands | `app/Console/Commands/` |
| Services | `app/Services/` |
| Form Requests | `app/Http/Requests/` |
