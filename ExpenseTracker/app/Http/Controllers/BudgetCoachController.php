<?php

namespace App\Http\Controllers;

use App\Models\BudgetLimit;
use App\Models\Expense;
use Illuminate\View\View;

class BudgetCoachController extends Controller
{
    // Show spending vs limits for the current month
    public function index(): View
    {
        // Get monthly spending per category
        $results = Expense::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $categoryTotals = [];
        foreach (Expense::CATEGORIES as $cat) {
            $categoryTotals[$cat] = $results[$cat] ?? 0.0;
        }

        // Get limits for this user, falling back to defaults
        $saved = BudgetLimit::where('user_id', auth()->id())
            ->pluck('amount', 'category')
            ->toArray();

        $limits = [];
        foreach (BudgetLimit::DEFAULTS as $cat => $default) {
            $limits[$cat] = (int) ($saved[$cat] ?? $default);
        }

        return view('coach.index', compact('categoryTotals', 'limits'));
    }
}
