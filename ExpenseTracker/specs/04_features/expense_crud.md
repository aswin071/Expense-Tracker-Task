# Feature: Expense CRUD

## Artisan Generation Commands
```
php artisan make:model Expense -m
php artisan make:controller ExpenseController --resource
php artisan make:request StoreExpenseRequest
php artisan make:request UpdateExpenseRequest
```

---

## Model: Expense
**File:** `app/Models/Expense.php`

```php
protected $fillable = [
    'user_id', 'amount', 'description',
    'category', 'date', 'receipt_image',
];

protected $casts = [
    'date' => 'datetime',
    'amount' => 'float',
];
```

### Category Constants (defined on the model)
```php
const CATEGORIES = [
    'food', 'transportation', 'entertainment',
    'health', 'shopping', 'utilities', 'other',
];
```

---

## Controller: ExpenseController
**File:** `app/Http/Controllers/ExpenseController.php`

### index()
- Query: `Expense::where('user_id', auth()->id())`
- Apply optional filters from query params:
  - `?month=6&year=2025` → `->whereMonth('date', $month)->whereYear('date', $year)`
  - `?category=food` → `->where('category', $category)`
- Order: `->latest('date')`
- Paginate: `->paginate(15)`
- Pass to view: `$expenses`, `$categories` (for filter dropdown), current filter values

### create()
- Pass `$categories = Expense::CATEGORIES` to the view
- Pass current category spending for awareness card (optional at this stage — see pre_spend_awareness.md)

### store()
- Validate via `StoreExpenseRequest`
- Handle receipt upload (see receipt_attachment.md)
- Create: `Expense::create([...$validated, 'user_id' => auth()->id()])`
- Redirect: `route('expenses.index')` with success message

### show()
- Lookup: `Expense::findOrFail($id)` then `abort(403)` if not owned
- Pass `$expense` to view

### edit()
- Same ownership check as show()
- Pass `$expense` and `$categories` to view

### update()
- Ownership check: `abort(403)` if `$expense->user_id !== auth()->id()`
- Validate via `UpdateExpenseRequest`
- Handle receipt replacement (delete old, store new if provided)
- `$expense->update([...$validated])`
- Redirect: `route('expenses.show', $expense)` with success message

### destroy()
- Ownership check
- Delete receipt file if exists: `Storage::disk('public')->delete($expense->receipt_image)`
- `$expense->delete()`
- Redirect: `route('expenses.index')` with success message

---

## Views
**Directory:** `resources/views/expenses/`

| File | Purpose |
|---|---|
| `index.blade.php` | Paginated list with month/category filter form |
| `create.blade.php` | New expense form (with awareness card) |
| `edit.blade.php` | Edit form with existing values pre-filled |
| `show.blade.php` | Single expense detail with receipt display |

All views extend `layouts/app.blade.php`.

### index.blade.php — key elements
- Filter form: `GET /expenses?month=&year=&category=`
- Table columns: Date, Description, Category, Amount, Actions (show/edit/delete)
- Delete uses a form with `@method('DELETE')` and `@csrf`
- `{{ $expenses->links() }}` for pagination

### create.blade.php — key elements
- Pre-spend awareness card (Alpine.js toggled — see pre_spend_awareness.md)
- `enctype="multipart/form-data"` on the form
- Category `<select>` populated from `$categories`
- Date `<input type="date">` defaulting to today

### edit.blade.php — key elements
- `@method('PUT')` inside form
- Pre-fill all fields with `old()` falling back to `$expense->field`
- Show existing receipt thumbnail if present with option to replace

### show.blade.php — key elements
- Display all expense fields
- Receipt display: `<img src="{{ Storage::url($expense->receipt_image) }}">`
- Edit and Delete buttons (delete as a form)

---

## Pagination
Uses Laravel's built-in `->paginate(15)` with Tailwind-styled links.  
Run: `php artisan vendor:publish --tag=laravel-pagination` if custom styling is needed.
