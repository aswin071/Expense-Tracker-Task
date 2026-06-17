<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\BudgetCoachService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(private BudgetCoachService $coachService) {}
    public function index(Request $request): View
    {
        $query = Expense::where('user_id', auth()->id());

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses   = $query->latest('date')->paginate(15)->withQueryString();
        $categories = Expense::CATEGORIES;

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create(): View
    {
        $categories     = Expense::CATEGORIES;
        $categoryTotals = $this->coachService->getMonthlySpendingByCategory(auth()->id());

        return view('expenses.create', compact('categories', 'categoryTotals'));
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('receipt_image')) {
            try {
                $validated['receipt_image'] = $request->file('receipt_image')
                    ->store('receipts/' . auth()->id(), 'public');
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['receipt_image' => 'Receipt upload failed. Please try again.']);
            }
        }

        Expense::create(array_merge($validated, ['user_id' => auth()->id()]));

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    public function show(Expense $expense): View
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Expense::CATEGORIES;

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        if ($request->hasFile('receipt_image')) {
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }

            try {
                $validated['receipt_image'] = $request->file('receipt_image')
                    ->store('receipts/' . auth()->id(), 'public');
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['receipt_image' => 'Receipt upload failed. Please try again.']);
            }
        } else {
            unset($validated['receipt_image']);
        }

        $expense->update($validated);

        return redirect()->route('expenses.show', $expense)->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
