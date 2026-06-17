# Error Handling

All errors are displayed inline within the same page. No separate error pages are used, except for Laravel's built-in 404 and 500 pages.

---

## Validation Errors

Handled automatically by Laravel when a Form Request fails. The user is redirected back to the form with:
- `$errors` bag (available in all Blade views via global `$errors`)
- `old()` input values for re-populating the form

**Display pattern:**
```blade
@error('field_name')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
```

Apply `border-red-500` or similar class to the input when it has an error:
```blade
<input class="@error('amount') border-red-500 @enderror ...">
```

---

## Authorization Errors (403)

When a user attempts to access a resource they do not own:

```php
if ($expense->user_id !== auth()->id()) {
    abort(403);
}
```

Laravel renders the `resources/views/errors/403.blade.php` view (or the default framework page if the view doesn't exist). A custom `403.blade.php` is optional.

**Never redirect silently** — a 403 should always terminate the request.

---

## Not Found Errors (404)

Use `findOrFail()` for all model lookups by primary key:

```php
$expense = Expense::findOrFail($id);
```

If the record doesn't exist, Laravel automatically throws a `ModelNotFoundException` which resolves to a 404 response. Do not use `find()` followed by a manual null check.

---

## File Upload Errors

Wrap storage operations in try/catch:

```php
try {
    $path = $request->file('receipt_image')
        ->store('receipts/' . auth()->id(), 'public');
} catch (\Exception $e) {
    return back()
        ->withInput()
        ->withErrors(['receipt_image' => 'Receipt upload failed. Please try again.']);
}
```

This surfaces the error inline using the standard `@error('receipt_image')` directive.

---

## CSRF Errors (419)

Occurs when a form is submitted without a valid CSRF token, or when the session expires.

- Every form must include `@csrf`
- Laravel's default 419 page is acceptable — no custom page needed
- If users frequently encounter this, increase `SESSION_LIFETIME` in `.env`

---

## Unauthenticated (401 → Redirect)

The `auth` middleware automatically redirects unauthenticated requests to `route('login')`. No explicit handling needed in controllers.

---

## Flash Messages (Success Feedback)

Use Laravel's session flash for success messages:

```php
// In controller after a successful action:
return redirect()->route('expenses.index')
    ->with('success', 'Expense deleted successfully.');
```

Display in the layout (`layouts/app.blade.php`):
```blade
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif
```

---

## Error Handling Checklist

| Scenario | Approach |
|---|---|
| Form validation fails | Form Request → redirect back with `$errors` + `old()` |
| Resource not found | `findOrFail()` → 404 |
| Unauthorized access | `abort(403)` |
| File upload fails | try/catch → `back()->withErrors(...)` |
| Unauthenticated | `auth` middleware → redirect to `/login` |
| Successful action | `redirect()->with('success', '...')` |
| Session expired | CSRF 419 page (default) |
