<?php

namespace App\Services;

use App\Models\BudgetLimit;
use App\Models\Expense;

class BudgetCoachService
{
    public function getMonthlySpendingByCategory(int $userId): array
    {
        $results = Expense::where('user_id', $userId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $totals = [];
        foreach (Expense::CATEGORIES as $cat) {
            $totals[$cat] = $results[$cat] ?? 0.0;
        }

        return $totals;
    }

    public function getSpendingForCategory(int $userId, string $category): float
    {
        return Expense::where('user_id', $userId)
            ->where('category', $category)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getLimitsForUser(int $userId): array
    {
        $saved = BudgetLimit::where('user_id', $userId)
            ->pluck('amount', 'category')
            ->toArray();

        $limits = [];
        foreach (BudgetLimit::DEFAULTS as $cat => $default) {
            $limits[$cat] = (int) ($saved[$cat] ?? $default);
        }

        return $limits;
    }
}
