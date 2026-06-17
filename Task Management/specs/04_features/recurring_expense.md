# Feature: Recurring Expenses

## Artisan Generation Commands
```
php artisan make:model RecurringExpense -m
php artisan make:controller RecurringExpenseController --resource
php artisan make:request StoreRecurringExpenseRequest
php artisan make:request UpdateRecurringExpenseRequest
php artisan make:command LogRecurringExpenses
```

---

## Model: RecurringExpense
**File:** `app/Models/RecurringExpense.php`

```php
protected $fillable = [
    'user_id', 'amount', 'description',
    'category', 'day_of_month', 'is_active', 'last_logged_at',
];

protected $casts = [
    'is_active' => 'boolean',
    'last_logged_at' => 'datetime',
    'amount' => 'float',
];
```

---

## Controller: RecurringExpenseController
**File:** `app/Http/Controllers/RecurringExpenseController.php`

Routes registered: `index`, `store`, `edit`, `update`, `destroy`, plus custom `toggle`.

### index()
- Query: `RecurringExpense::where('user_id', auth()->id())->latest()->get()`
- Pass to `resources/views/recurring/index.blade.php`
- Also pass an empty `$recurringExpense` (new model instance) so the create form can live on the same page

### store()
- Validate via `StoreRecurringExpenseRequest`
- `RecurringExpense::create([...$validated, 'user_id' => auth()->id()])`
- Redirect: `route('recurring.index')` with success message

### edit()
- Ownership check: `abort(403)` if not owned
- Pass `$recurringExpense` and categories to `resources/views/recurring/edit.blade.php`

### update()
- Ownership check
- Validate via `UpdateRecurringExpenseRequest`
- `$recurringExpense->update($validated)`
- Redirect: `route('recurring.index')`

### destroy()
- Ownership check
- `$recurringExpense->delete()`
- Redirect: `route('recurring.index')`

### toggle()
- Ownership check
- `$recurringExpense->update(['is_active' => !$recurringExpense->is_active])`
- Redirect: `route('recurring.index')`

---

## Artisan Command: LogRecurringExpenses
**File:** `app/Console/Commands/LogRecurringExpenses.php`  
**Signature:** `expenses:log-recurring`

### Logic
```php
public function handle(): void
{
    $today = now()->day;
    $thisMonth = now()->month;
    $thisYear = now()->year;

    RecurringExpense::where('is_active', true)
        ->where('day_of_month', $today)
        ->get()
        ->each(function (RecurringExpense $recurring) use ($thisMonth, $thisYear) {
            // Skip if already logged this month
            $alreadyLogged = Expense::where('user_id', $recurring->user_id)
                ->where('description', $recurring->description)
                ->whereMonth('date', $thisMonth)
                ->whereYear('date', $thisYear)
                ->where('amount', $recurring->amount)
                ->exists();

            if ($alreadyLogged) {
                return;
            }

            Expense::create([
                'user_id'     => $recurring->user_id,
                'amount'      => $recurring->amount,
                'description' => $recurring->description,
                'category'    => $recurring->category,
                'date'        => now(),
            ]);

            $recurring->update(['last_logged_at' => now()]);
        });

    $this->info('Recurring expenses logged for ' . now()->toDateString());
}
```

---

## Scheduler Registration
**File:** `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('expenses:log-recurring')->dailyAt('00:01');
}
```

To run the scheduler locally (must remain running):
```
php artisan schedule:work
```

To test the command manually:
```
php artisan expenses:log-recurring
```

---

## Dashboard Banner

On the dashboard, show a banner when recurring expenses were auto-logged today:

```php
// DashboardController@index
$autoLoggedToday = Expense::where('user_id', auth()->id())
    ->whereDate('created_at', today())
    ->whereNotNull('user_id') // all auto-logged have same created_at as date
    ->count();
```

Or simpler: check `last_logged_at` date on RecurringExpense:

```php
$autoLoggedToday = RecurringExpense::where('user_id', auth()->id())
    ->whereDate('last_logged_at', today())
    ->count();
```

Pass `$autoLoggedToday` to the dashboard view and show a banner if `> 0`:

```blade
@if ($autoLoggedToday > 0)
    <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded mb-4">
        {{ $autoLoggedToday }} recurring expense(s) were automatically logged today.
    </div>
@endif
```

---

## Views
**Directory:** `resources/views/recurring/`

| File | Purpose |
|---|---|
| `index.blade.php` | List all recurring expenses + inline create form |
| `edit.blade.php` | Edit an existing recurring expense |

All views extend `layouts/app.blade.php`.
