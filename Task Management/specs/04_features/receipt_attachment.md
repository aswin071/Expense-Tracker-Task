# Feature: Receipt Attachment

## Setup
```
php artisan storage:link
```
This creates a symlink from `public/storage` → `storage/app/public` so uploaded files are web-accessible.

---

## Form Requirements

Both the create and edit expense forms must include:
```html
<form method="POST" enctype="multipart/form-data" ...>
```
Without `enctype="multipart/form-data"` the file will not be transmitted.

---

## Validation Rule (StoreExpenseRequest & UpdateExpenseRequest)
```php
'receipt_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
```
- `nullable` — receipt is optional
- `file` — must be an uploaded file object
- `mimes:jpg,jpeg,png,pdf` — only these formats accepted
- `max:2048` — maximum 2 MB

---

## Storing a File (store / update)

```php
if ($request->hasFile('receipt_image')) {
    $path = $request->file('receipt_image')
        ->store('receipts/' . auth()->id(), 'public');
    // $path is relative to storage/app/public, e.g. "receipts/1/filename.jpg"
}
```

- Disk: `public` (maps to `storage/app/public/`)
- Directory: `receipts/{user_id}/` — keeps each user's files isolated
- The returned `$path` string is stored in `expenses.receipt_image`

---

## Replacing a Receipt on Update

```php
if ($request->hasFile('receipt_image')) {
    // Delete old file if it exists
    if ($expense->receipt_image) {
        Storage::disk('public')->delete($expense->receipt_image);
    }
    // Store new file
    $validated['receipt_image'] = $request->file('receipt_image')
        ->store('receipts/' . auth()->id(), 'public');
}
```

---

## Deleting a Receipt (destroy)

```php
if ($expense->receipt_image) {
    Storage::disk('public')->delete($expense->receipt_image);
}
$expense->delete();
```

---

## Displaying the Receipt (show.blade.php)

```blade
@if ($expense->receipt_image)
    @php $ext = pathinfo($expense->receipt_image, PATHINFO_EXTENSION); @endphp

    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
        <img src="{{ Storage::url($expense->receipt_image) }}"
             alt="Receipt"
             class="max-w-sm rounded shadow">
    @else
        <a href="{{ Storage::url($expense->receipt_image) }}"
           target="_blank"
           class="text-blue-600 underline">
            View Receipt (PDF)
        </a>
    @endif
@else
    <p class="text-gray-400">No receipt attached.</p>
@endif
```

---

## Error Handling

Wrap file storage in a try/catch in case of disk errors:

```php
try {
    $path = $request->file('receipt_image')
        ->store('receipts/' . auth()->id(), 'public');
} catch (\Exception $e) {
    return back()->withErrors(['receipt_image' => 'Failed to upload receipt. Please try again.']);
}
```

---

## Storage Path Summary

| Concept | Value |
|---|---|
| Disk name | `public` |
| Physical path | `storage/app/public/receipts/{user_id}/` |
| Web URL | `/storage/receipts/{user_id}/filename.ext` |
| Helper | `Storage::url($expense->receipt_image)` |
| Symlink command | `php artisan storage:link` |
