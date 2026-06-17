# Database Schema

## Database Engine
SQLite — configured in `config/database.php` and `.env`:
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

---

## Table: users
Managed by Laravel Breeze migration. No changes needed.

| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | Primary key |
| name | string | |
| email | string | unique |
| email_verified_at | timestamp | nullable |
| password | string | hashed |
| remember_token | string | nullable |
| timestamps | | created_at, updated_at |

---

## Table: expenses

### Migration command
```
php artisan make:migration create_expenses_table
```

| Column | Type | Constraints |
|---|---|---|
| id | bigIncrements | Primary key |
| user_id | foreignId | constrained('users'), cascadeOnDelete |
| amount | float | not null |
| description | text | not null |
| category | enum | not null — see values below |
| date | timestamp | not null |
| receipt_image | string | nullable |
| created_at | timestamp | auto |
| updated_at | timestamp | auto |

### Category enum values
`food`, `transportation`, `entertainment`, `health`, `shopping`, `utilities`, `other`

### Indexes
```php
$table->index(['user_id', 'date']);
$table->index(['user_id', 'category']);
```

---

## Table: recurring_expenses

### Migration command
```
php artisan make:migration create_recurring_expenses_table
```

| Column | Type | Constraints |
|---|---|---|
| id | bigIncrements | Primary key |
| user_id | foreignId | constrained('users'), cascadeOnDelete |
| amount | float | not null |
| description | text | not null |
| category | enum | not null — same values as expenses |
| day_of_month | integer | 1–28 |
| is_active | boolean | default: true |
| last_logged_at | timestamp | nullable |
| created_at | timestamp | auto |
| updated_at | timestamp | auto |

### Indexes
```php
$table->index(['user_id', 'is_active']);
```

---

## Migration Run Order
1. `users` table (Breeze default)
2. `expenses` table
3. `recurring_expenses` table

Run all with:
```
php artisan migrate
```

---

## Model Relationships

### User → Expenses
```php
// app/Models/User.php
public function expenses(): HasMany
{
    return $this->hasMany(Expense::class);
}

public function recurringExpenses(): HasMany
{
    return $this->hasMany(RecurringExpense::class);
}
```

### Expense → User
```php
// app/Models/Expense.php
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### RecurringExpense → User
```php
// app/Models/RecurringExpense.php
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```
