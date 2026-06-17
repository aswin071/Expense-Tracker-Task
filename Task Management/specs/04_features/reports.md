# Feature: Reports

## Artisan Generation Commands
```
php artisan make:controller ReportController
```

---

## Controller: ReportController
**File:** `app/Http/Controllers/ReportController.php`

---

### index()
Landing page for reports — links to monthly and all-time reports.

**View:** `resources/views/reports/index.blade.php`

---

### monthly()
**Route:** `GET /reports/monthly?month=6&year=2025`

#### Query Parameters
| Param | Default | Notes |
|---|---|---|
| `month` | `now()->month` | 1–12 |
| `year` | `now()->year` | 4-digit year |

#### Report 1 — Category Totals for the Month
```php
$categoryTotals = Expense::where('user_id', auth()->id())
    ->whereMonth('date', $month)
    ->whereYear('date', $year)
    ->selectRaw('category, SUM(amount) as total')
    ->groupBy('category')
    ->pluck('total', 'category')
    ->toArray();
```

#### Report 2 — Average Daily Spend
```php
$monthlyTotal = array_sum($categoryTotals);
$daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
$dailyAverage = $daysInMonth > 0 ? $monthlyTotal / $daysInMonth : 0;
```

#### Report 3 — All-Time Per Category (shown on same page as a secondary section)
```php
$allTimeTotals = Expense::where('user_id', auth()->id())
    ->selectRaw('category, SUM(amount) as total')
    ->groupBy('category')
    ->pluck('total', 'category')
    ->toArray();
```

#### Pass to View
```php
return view('reports.monthly', compact(
    'categoryTotals',
    'allTimeTotals',
    'monthlyTotal',
    'dailyAverage',
    'month',
    'year'
));
```

---

## Views
**Directory:** `resources/views/reports/`

| File | Purpose |
|---|---|
| `index.blade.php` | Report hub / navigation |
| `monthly.blade.php` | Monthly breakdown + all-time summary |

All views extend `layouts/app.blade.php`.

---

## Chart.js Integration

Load via CDN in the layout or directly in the report view:

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

### Monthly Bar Chart (monthly.blade.php)

```blade
<canvas id="monthlyChart" class="max-h-64"></canvas>

<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json(array_keys($categoryTotals)),
        datasets: [{
            label: 'Spending (₹)',
            data: @json(array_values($categoryTotals)),
            backgroundColor: 'rgba(99, 102, 241, 0.6)',
            borderColor: 'rgba(99, 102, 241, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
```

### All-Time Bar Chart

Same pattern as above, using `$allTimeTotals` with a different canvas id and a distinct color.

---

## Month Filter Form

```blade
<form method="GET" action="{{ route('reports.monthly') }}" class="flex gap-3 mb-6">
    <select name="month" class="border rounded px-3 py-2">
        @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
            </option>
        @endforeach
    </select>

    <select name="year" class="border rounded px-3 py-2">
        @foreach(range(now()->year - 2, now()->year) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>

    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
        View Report
    </button>
</form>
```

---

## Summary Statistics Display

```blade
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded shadow p-4 text-center">
        <p class="text-sm text-gray-500">Total This Month</p>
        <p class="text-2xl font-bold text-indigo-600">₹{{ number_format($monthlyTotal, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-4 text-center">
        <p class="text-sm text-gray-500">Daily Average</p>
        <p class="text-2xl font-bold text-green-600">₹{{ number_format($dailyAverage, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-4 text-center">
        <p class="text-sm text-gray-500">Categories Used</p>
        <p class="text-2xl font-bold text-gray-700">{{ count($categoryTotals) }}</p>
    </div>
</div>
```
