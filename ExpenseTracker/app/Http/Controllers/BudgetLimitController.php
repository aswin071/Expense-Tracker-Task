<?php

namespace App\Http\Controllers;

use App\Models\BudgetLimit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetLimitController extends Controller
{
    // Show the form to edit budget limits
    public function edit(): View
    {
        // Get limits for this user, falling back to defaults
        $saved = BudgetLimit::where('user_id', auth()->id())
            ->pluck('amount', 'category')
            ->toArray();

        $limits = [];
        foreach (BudgetLimit::DEFAULTS as $cat => $default) {
            $limits[$cat] = (int) ($saved[$cat] ?? $default);
        }

        return view('coach.limits', compact('limits'));
    }

    // Save updated budget limits
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

    // Reset all limits back to defaults
    public function reset(): RedirectResponse
    {
        BudgetLimit::where('user_id', auth()->id())->delete();

        return redirect()->route('coach.limits')->with('success', 'Limits reset to defaults.');
    }
}
