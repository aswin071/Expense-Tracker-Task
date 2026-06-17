<?php

namespace App\Http\Controllers;

use App\Services\BudgetCoachService;
use Illuminate\View\View;

class BudgetCoachController extends Controller
{
    public function __construct(private BudgetCoachService $coachService) {}

    public function index(): View
    {
        $categoryTotals = $this->coachService->getMonthlySpendingByCategory(auth()->id());
        $limits         = $this->coachService->getLimitsForUser(auth()->id());

        return view('coach.index', compact('categoryTotals', 'limits'));
    }
}
