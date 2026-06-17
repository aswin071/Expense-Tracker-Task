<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function monthly(): View
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year', now()->year);

        $categoryTotals = Expense::where('user_id', auth()->id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        $monthlyTotal = $categoryTotals->sum('total');

        $daysInMonth  = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $dailyAverage = $daysInMonth > 0 ? $monthlyTotal / $daysInMonth : 0;

        $allTimeTotals = Expense::where('user_id', auth()->id())
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        return view('reports.monthly', compact(
            'categoryTotals',
            'allTimeTotals',
            'monthlyTotal',
            'dailyAverage',
            'month',
            'year'
        ));
    }
}
