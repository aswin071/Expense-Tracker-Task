<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Services\BudgetCoachService;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private BudgetCoachService $coachService) {}

    public function index(): View
    {
        $todayStart = Carbon::today()->toDateTimeString();

        $autoLogged = Expense::where('user_id', auth()->id())
            ->where('description', 'like', '%(Auto)')
            ->where('date', '>=', $todayStart)
            ->get();

        $monthlyTotal = Expense::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $categoryTotals = $this->coachService->getMonthlySpendingByCategory(auth()->id());

        $recentExpenses = Expense::where('user_id', auth()->id())
            ->latest('date')
            ->limit(5)
            ->get();

        $recurringExpenses = RecurringExpense::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('day_of_month')
            ->get();

        return view('dashboard', compact(
            'autoLogged', 'monthlyTotal', 'categoryTotals', 'recentExpenses', 'recurringExpenses'
        ));
    }
}
