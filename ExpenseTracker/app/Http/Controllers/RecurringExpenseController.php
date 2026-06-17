<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecurringExpenseRequest;
use App\Http\Requests\UpdateRecurringExpenseRequest;
use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RecurringExpenseController extends Controller
{
    public function index(): View
    {
        $recurring = RecurringExpense::where('user_id', auth()->id())
            ->orderBy('day_of_month')
            ->get();

        return view('recurring.index', compact('recurring'));
    }

    public function store(StoreRecurringExpenseRequest $request): RedirectResponse
    {
        RecurringExpense::create(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));

        return redirect()->route('recurring.index')
            ->with('success', 'Recurring expense added.');
    }

    public function update(UpdateRecurringExpenseRequest $request, RecurringExpense $recurring): RedirectResponse
    {
        abort_if($recurring->user_id !== auth()->id(), 403);

        $recurring->update($request->validated());

        return redirect()->route('recurring.index')
            ->with('success', 'Recurring expense updated.');
    }

    public function destroy(RecurringExpense $recurring): RedirectResponse
    {
        abort_if($recurring->user_id !== auth()->id(), 403);

        $recurring->delete();

        return redirect()->route('recurring.index')
            ->with('success', 'Recurring expense deleted.');
    }

    public function toggle(RecurringExpense $recurring): RedirectResponse
    {
        abort_if($recurring->user_id !== auth()->id(), 403);

        $recurring->update(['is_active' => ! $recurring->is_active]);

        return back()->with('success', $recurring->is_active ? 'Activated.' : 'Paused.');
    }

    public function markPaid(RecurringExpense $recurring): RedirectResponse
    {
        abort_if($recurring->user_id !== auth()->id(), 403);

        // Only mark paid once per month
        if ($recurring->last_logged_at &&
            $recurring->last_logged_at->month === now()->month &&
            $recurring->last_logged_at->year === now()->year) {
            return back()->with('error', 'Already marked as paid this month.');
        }

        Expense::create([
            'user_id'     => auth()->id(),
            'amount'      => $recurring->amount,
            'description' => $recurring->description . ' (Manual)',
            'category'    => $recurring->category,
            'date'        => Carbon::today(),
        ]);

        $recurring->update(['last_logged_at' => now()]);

        return back()->with('success', "₹" . number_format($recurring->amount, 0) . " for {$recurring->description} added to your expenses.");
    }
}
