<?php

namespace App\Http\Controllers;

use App\Models\BudgetLimit;
use App\Services\BudgetCoachService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetLimitController extends Controller
{
    public function __construct(private BudgetCoachService $coachService) {}

    public function edit(): View
    {
        $limits = $this->coachService->getLimitsForUser(auth()->id());

        return view('coach.limits', compact('limits'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'limits'   => ['required', 'array'],
            'limits.*' => ['required', 'integer', 'min:100', 'max:999999'],
        ]);

        foreach ($data['limits'] as $category => $amount) {
            if (!array_key_exists($category, BudgetLimit::DEFAULTS)) {
                continue;
            }

            BudgetLimit::updateOrCreate(
                ['user_id' => auth()->id(), 'category' => $category],
                ['amount'  => $amount]
            );
        }

        return redirect()->route('coach.index')->with('success', 'Budget limits updated successfully.');
    }

    public function reset(): RedirectResponse
    {
        BudgetLimit::where('user_id', auth()->id())->delete();

        return redirect()->route('coach.limits')->with('success', 'Limits reset to defaults.');
    }
}
