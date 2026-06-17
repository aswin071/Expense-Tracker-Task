# Routes (routes/web.php)

All routes are defined in `routes/web.php`. No API routes file is used.

---

## Guest Routes (no middleware)

| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/` | — | Redirect to `/dashboard` |
| GET | `/register` | `Auth\RegisteredUserController` | `create` |
| POST | `/register` | `Auth\RegisteredUserController` | `store` |
| GET | `/login` | `Auth\AuthenticatedSessionController` | `create` |
| POST | `/login` | `Auth\AuthenticatedSessionController` | `store` |
| POST | `/logout` | `Auth\AuthenticatedSessionController` | `destroy` |

> These are registered automatically by Breeze. Do not manually duplicate them.

---

## Protected Routes (middleware: `auth`)

### Dashboard
| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/dashboard` | `DashboardController` | `index` |

### Expenses (Resource)
| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/expenses` | `ExpenseController` | `index` |
| GET | `/expenses/create` | `ExpenseController` | `create` |
| POST | `/expenses` | `ExpenseController` | `store` |
| GET | `/expenses/{expense}` | `ExpenseController` | `show` |
| GET | `/expenses/{expense}/edit` | `ExpenseController` | `edit` |
| PUT | `/expenses/{expense}` | `ExpenseController` | `update` |
| DELETE | `/expenses/{expense}` | `ExpenseController` | `destroy` |

### Recurring Expenses (Partial Resource + custom toggle)
| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/recurring` | `RecurringExpenseController` | `index` |
| POST | `/recurring` | `RecurringExpenseController` | `store` |
| GET | `/recurring/{recurring}/edit` | `RecurringExpenseController` | `edit` |
| PUT | `/recurring/{recurring}` | `RecurringExpenseController` | `update` |
| DELETE | `/recurring/{recurring}` | `RecurringExpenseController` | `destroy` |
| PATCH | `/recurring/{recurring}/toggle` | `RecurringExpenseController` | `toggle` |

### Reports
| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/reports` | `ReportController` | `index` |
| GET | `/reports/monthly` | `ReportController` | `monthly` |

### Pre-Spend Coach
| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/coach` | `BudgetCoachController` | `index` |

---

## Named Routes Reference

| Name | URI |
|---|---|
| `dashboard` | `/dashboard` |
| `expenses.index` | `/expenses` |
| `expenses.create` | `/expenses/create` |
| `expenses.store` | `/expenses` |
| `expenses.show` | `/expenses/{expense}` |
| `expenses.edit` | `/expenses/{expense}/edit` |
| `expenses.update` | `/expenses/{expense}` |
| `expenses.destroy` | `/expenses/{expense}` |
| `recurring.index` | `/recurring` |
| `recurring.store` | `/recurring` |
| `recurring.edit` | `/recurring/{recurring}/edit` |
| `recurring.update` | `/recurring/{recurring}` |
| `recurring.destroy` | `/recurring/{recurring}` |
| `recurring.toggle` | `/recurring/{recurring}/toggle` |
| `reports.index` | `/reports` |
| `reports.monthly` | `/reports/monthly` |
| `coach.index` | `/coach` |

---

## Route Registration Pattern

```php
// routes/web.php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BudgetCoachController;
use App\Http\Controllers\DashboardController;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__.'/auth.php'; // Breeze auth routes

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('expenses', ExpenseController::class);

    Route::resource('recurring', RecurringExpenseController::class)
        ->except(['show', 'create']);
    Route::patch('/recurring/{recurring}/toggle', [RecurringExpenseController::class, 'toggle'])
        ->name('recurring.toggle');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');

    Route::get('/coach', [BudgetCoachController::class, 'index'])->name('coach.index');
});
```
