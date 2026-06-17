# Feature: Pre-Spend Awareness

## Purpose
Before the user submits a new expense, show them how much they have already spent in the same category this month, so they can make an informed decision.

---

## Service: BudgetCoachService
**File:** `app/Services/BudgetCoachService.php`

### Generation
No artisan command for Services — create the file manually in `app/Services/`.

### Interface

```php
namespace App\Services;

use App\Models\Expense;

class BudgetCoachService
{
    public function getMonthlySpending(int $userId, string $category): float
    {
        return Expense::where('user_id', $userId)
            ->where('category', $category)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getAllCategoryTotals(int $userId): array
    {
        $results = Expense::where('user_id', $userId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Return 0 for categories with no spending
        $totals = [];
        foreach (\App\Models\Expense::CATEGORIES as $cat) {
            $totals[$cat] = $results[$cat] ?? 0.0;
        }
        return $totals;
    }
}
```

---

## Controller: BudgetCoachController
**File:** `app/Http/Controllers/BudgetCoachController.php`

```
php artisan make:controller BudgetCoachController
```

### Constructor Injection
```php
public function __construct(private BudgetCoachService $coach) {}
```

### index()
```php
public function index(): View
{
    $categoryTotals = $this->coach->getAllCategoryTotals(auth()->id());
    return view('coach.index', compact('categoryTotals'));
}
```

---

## Service Provider Binding
Register `BudgetCoachService` in `app/Providers/AppServiceProvider.php`:

```php
public function register(): void
{
    $this->app->bind(BudgetCoachService::class, BudgetCoachService::class);
}
```

Laravel's automatic resolution via the container handles constructor injection — the explicit bind is optional but recommended for clarity.

---

## Awareness Card on Expense Create Page

In `ExpenseController@create`, inject and use the service:

```php
public function __construct(private BudgetCoachService $coach) {}

public function create(): View
{
    $categoryTotals = $this->coach->getAllCategoryTotals(auth()->id());
    $categories = Expense::CATEGORIES;
    return view('expenses.create', compact('categories', 'categoryTotals'));
}
```

### Blade Awareness Card (create.blade.php)

Uses **Alpine.js** (bundled with Breeze) for the show/hide toggle:

```blade
<div x-data="{ aware: false, selectedCategory: '' }">

    {{-- Awareness Card --}}
    <div x-show="!aware" class="bg-yellow-50 border border-yellow-300 rounded p-4 mb-6">
        <h3 class="font-semibold text-yellow-800 mb-2">Monthly Spending Awareness</h3>

        <div class="grid grid-cols-2 gap-2 text-sm text-yellow-700 mb-4">
            @foreach ($categoryTotals as $cat => $total)
                <div class="capitalize">{{ $cat }}</div>
                <div class="font-medium">₹{{ number_format($total, 2) }}</div>
            @endforeach
        </div>

        <button type="button"
                @click="aware = true"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
            I'm aware, proceed
        </button>
    </div>

    {{-- Expense Form (hidden until aware) --}}
    <div x-show="aware" x-cloak>
        {{-- The full expense creation form goes here --}}
    </div>

</div>
```

Add `[x-cloak] { display: none; }` to your CSS (or app.css) to prevent flash on page load.

---

## Coach Page (coach/index.blade.php)

A dedicated `/coach` page shows a full breakdown of this month's spending by category, with visual emphasis on high-spend categories:

```blade
@foreach ($categoryTotals as $category => $total)
    <div class="flex justify-between items-center p-3 border rounded mb-2
        {{ $total > 5000 ? 'bg-red-50 border-red-300' : 'bg-white' }}">
        <span class="capitalize font-medium">{{ $category }}</span>
        <span class="font-bold">₹{{ number_format($total, 2) }}</span>
    </div>
@endforeach
```

The ₹5000 threshold is a simple heuristic — it can be made configurable later.

---

## No External APIs
All awareness data comes from the local SQLite database. No budget limits are stored — this is purely informational, showing actuals.
