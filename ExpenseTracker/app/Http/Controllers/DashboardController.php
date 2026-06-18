<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // Show the dashboard with monthly total, recent expenses, and recurring schedule
    public function index(): View
    {
        // Get monthly total
        $monthlyTotal = Expense::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Get recent expenses
        $recentExpenses = Expense::where('user_id', auth()->id())
            ->latest('date')
            ->limit(5)
            ->get();

        // Get active recurring expenses
        $recurring = RecurringExpense::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('day_of_month')
            ->get();

        return view('dashboard', compact('monthlyTotal', 'recentExpenses', 'recurring'));
    }
}
