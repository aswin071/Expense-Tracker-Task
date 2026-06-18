<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    // Show all expenses for the logged in user with optional filters
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

    // Show the create expense form with monthly total
    public function create(): View
    {
        $categories = Expense::CATEGORIES;

        // Get monthly total to show on the create form
        $spent = Expense::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('expenses.create', compact('categories', 'spent'));
    }

    // Save a new expense
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('receipt_image')) {
            try {
                $data['receipt_image'] = $request->file('receipt_image')
                    ->store('receipts/' . auth()->id(), 'public');
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['receipt_image' => 'Receipt upload failed. Please try again.']);
            }
        }

        Expense::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    // Show a single expense
    public function show(Expense $expense): View
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        return view('expenses.show', compact('expense'));
    }

    // Show the edit form for an expense
    public function edit(Expense $expense): View
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Expense::CATEGORIES;

        return view('expenses.edit', compact('expense', 'categories'));
    }

    // Update an expense
    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validated();

        if ($request->hasFile('receipt_image')) {
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }

            try {
                $data['receipt_image'] = $request->file('receipt_image')
                    ->store('receipts/' . auth()->id(), 'public');
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['receipt_image' => 'Receipt upload failed. Please try again.']);
            }
        } else {
            unset($data['receipt_image']);
        }

        $expense->update($data);

        return redirect()->route('expenses.show', $expense)->with('success', 'Expense updated successfully.');
    }

    // Delete an expense
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
