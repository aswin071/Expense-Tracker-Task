# ExpenseTracker

A mobile-first personal expense tracking web app built with Laravel 10, Tailwind CSS, and Alpine.js.

---

## Requirements

- PHP 8.1+
- Composer
- Node.js 18+
- SQLite (bundled with PHP)

---

## Installation

```bash
git clone <repo>
cd ExpenseTracker

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link

php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## Demo Login

| Field    | Value               |
|----------|---------------------|
| Email    | demo@example.com    |
| Password | password            |

The seeder creates 30 sample expenses spread over the last 60 days, plus 3 recurring expenses (Netflix, Rent, Gym).

---

## Features

### Auth
- Register, Login, Logout (Laravel Breeze)
- Session-based access control — all routes require authentication
- Per-user data isolation — users cannot access each other's data (403)

### Expense CRUD
- Create expenses with amount, category, description, date
- Optional receipt attachment (JPG, PNG, PDF — max 2 MB)
- List view with filters by month, year, and category
- Edit and delete with receipt replacement support

### Recurring Expenses
- Define monthly auto-logged expenses (day of month, 1–28)
- Toggle active/inactive per recurring item
- Scheduler runs daily at 00:01 to auto-create expenses
- Dashboard banner shows count of expenses auto-logged today

### Pre-Spend Awareness
- Before the add-expense form, a card shows current month's spending per category
- Card is skipped automatically when no spending exists yet this month
- Full breakdown available at `/coach`

### Budget Coach
- Per-category spending vs configurable monthly limits
- Colour-coded cards: green (< 50%), yellow (50–80%), red (≥ 80%)
- Manage your own limits at `/coach/limits` (saved per user, defaults provided)
- Reset to defaults available

### Monthly Reports
- Bar chart (Chart.js) of spending by category
- Daily average and monthly total
- Month-by-month arrow navigation
- All-time cumulative totals per category

### AI Bill Scanner *(Upcoming)*
- Frontend preview at `/scan` — feature is not yet implemented
- Planned: upload JPG / PDF / Excel, AI extracts and pre-fills the expense form

---

## Test Recurring Command Manually

```bash
php artisan expenses:log-recurring
```

This creates one expense per active recurring entry whose `day_of_month` matches today. Running it twice on the same day is idempotent — no duplicates.

---

## Re-seed the Database

```bash
php artisan migrate:fresh --seed
```

This drops all tables, re-runs migrations, and re-seeds demo data.

---

## Project Structure

```
app/
  Http/Controllers/
    DashboardController.php       # Home page + recurring/monthly summary
    ExpenseController.php         # Expense CRUD
    RecurringExpenseController.php# Recurring management + toggle
    BudgetCoachController.php     # Coach overview
    BudgetLimitController.php     # Manage per-category limits
    ReportController.php          # Reports hub + monthly breakdown
  Models/
    Expense.php
    RecurringExpense.php
    BudgetLimit.php
  Services/
    BudgetCoachService.php        # Monthly spending queries + limit lookup
  Console/Commands/
    LogRecurringExpenses.php      # php artisan expenses:log-recurring

resources/views/
  layouts/app.blade.php           # Fixed top bar + bottom nav with FAB
  dashboard.blade.php
  expenses/{create,edit,show,index}.blade.php
  recurring/index.blade.php
  coach/{index,limits}.blade.php
  reports/{index,monthly}.blade.php
  scan/index.blade.php            # AI Scanner (Upcoming — frontend only)
  auth/{login,register}.blade.php

database/
  migrations/                     # 4 tables: users, expenses, recurring_expenses, budget_limits
  seeders/DatabaseSeeder.php
```

---

## Specs

All feature specifications are in the `/specs` folder:

| File | Contents |
|------|----------|
| `02_database_schema.md` | Table definitions and relationships |
| `03_api_routes.md` | All application routes |
| `04_features/` | Per-feature specs (auth, CRUD, receipts, recurring, coach, reports) |
| `05_validation_rules.md` | All form validation rules |
| `07_test_plan.md` | Manual verification checklist |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 10 |
| Auth | Laravel Breeze (Blade stack) |
| Database | SQLite (Eloquent ORM) |
| Frontend | Tailwind CSS + Alpine.js (via Vite) |
| Charts | Chart.js (CDN) |
| Timezone | Asia/Kolkata |
