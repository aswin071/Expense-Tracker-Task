<?php

use App\Http\Controllers\BudgetCoachController;
use App\Http\Controllers\BudgetLimitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('expenses.index'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('expenses', ExpenseController::class);

    Route::resource('recurring', RecurringExpenseController::class)->except(['show', 'create', 'edit']);
    Route::patch('recurring/{recurring}/toggle', [RecurringExpenseController::class, 'toggle'])->name('recurring.toggle');
    Route::post('recurring/{recurring}/mark-paid', [RecurringExpenseController::class, 'markPaid'])->name('recurring.markPaid');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');

    Route::get('/coach', [BudgetCoachController::class, 'index'])->name('coach.index');
    Route::get('/coach/limits', [BudgetLimitController::class, 'edit'])->name('coach.limits');
    Route::post('/coach/limits', [BudgetLimitController::class, 'update'])->name('coach.limits.update');
    Route::delete('/coach/limits', [BudgetLimitController::class, 'reset'])->name('coach.limits.reset');

    Route::get('/scan', fn () => view('scan.index'))->name('scan.index');
});

require __DIR__.'/auth.php';
