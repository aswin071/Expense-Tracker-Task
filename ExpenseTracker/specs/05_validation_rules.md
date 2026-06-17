# Validation Rules

All validation is handled by Laravel Form Request classes. Controllers never call `$request->validate()` directly.

---

## StoreExpenseRequest
**File:** `app/Http/Requests/StoreExpenseRequest.php`  
**Command:** `php artisan make:request StoreExpenseRequest`

```php
public function authorize(): bool
{
    return true; // Auth middleware already handles authentication
}

public function rules(): array
{
    return [
        'amount'        => 'required|numeric|min:0.01',
        'description'   => 'required|string|max:500',
        'category'      => 'required|in:food,transportation,entertainment,health,shopping,utilities,other',
        'date'          => 'required|date|before_or_equal:today',
        'receipt_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];
}
```

---

## UpdateExpenseRequest
**File:** `app/Http/Requests/UpdateExpenseRequest.php`  
**Command:** `php artisan make:request UpdateExpenseRequest`

Identical rules to `StoreExpenseRequest`. The `receipt_image` field remains nullable so users can update other fields without re-uploading a receipt.

```php
public function authorize(): bool
{
    return true;
}

public function rules(): array
{
    return [
        'amount'        => 'required|numeric|min:0.01',
        'description'   => 'required|string|max:500',
        'category'      => 'required|in:food,transportation,entertainment,health,shopping,utilities,other',
        'date'          => 'required|date|before_or_equal:today',
        'receipt_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];
}
```

---

## StoreRecurringExpenseRequest
**File:** `app/Http/Requests/StoreRecurringExpenseRequest.php`  
**Command:** `php artisan make:request StoreRecurringExpenseRequest`

```php
public function authorize(): bool
{
    return true;
}

public function rules(): array
{
    return [
        'amount'       => 'required|numeric|min:0.01',
        'description'  => 'required|string|max:500',
        'category'     => 'required|in:food,transportation,entertainment,health,shopping,utilities,other',
        'day_of_month' => 'required|integer|min:1|max:28',
    ];
}
```

> `max:28` ensures the day exists in every month (including February).

---

## UpdateRecurringExpenseRequest
**File:** `app/Http/Requests/UpdateRecurringExpenseRequest.php`  
**Command:** `php artisan make:request UpdateRecurringExpenseRequest`

Same rules as `StoreRecurringExpenseRequest`.

---

## Auth Validation (managed by Breeze)

| Field | Rules |
|---|---|
| `name` | `required\|string\|max:255` |
| `email` | `required\|string\|email\|max:255\|unique:users` |
| `password` | `required\|string\|min:8\|confirmed` |
| `password_confirmation` | must match `password` (handled by `confirmed` rule) |

---

## Displaying Validation Errors in Blade Views

Use the `@error` directive for field-level errors:

```blade
<div>
    <label for="amount">Amount</label>
    <input type="number"
           name="amount"
           id="amount"
           value="{{ old('amount') }}"
           class="border rounded px-3 py-2 w-full @error('amount') border-red-500 @enderror">
    @error('amount')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
```

### General Error Summary (optional, at top of form)

```blade
@if ($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700 rounded p-4 mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

---

## Custom Error Messages (optional)

Override default messages in the Form Request's `messages()` method:

```php
public function messages(): array
{
    return [
        'amount.min'           => 'Amount must be at least ₹0.01.',
        'date.before_or_equal' => 'You cannot log an expense for a future date.',
        'receipt_image.max'    => 'Receipt file must not exceed 2 MB.',
        'day_of_month.max'     => 'Day must be between 1 and 28 to work in all months.',
    ];
}
```
