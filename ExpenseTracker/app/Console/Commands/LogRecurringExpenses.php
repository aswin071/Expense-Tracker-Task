<?php

namespace App\Console\Commands;

use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class LogRecurringExpenses extends Command
{
    protected $signature = 'expenses:log-recurring';

    protected $description = 'Auto-log active recurring expenses whose day_of_month matches today';

    public function handle(): int
    {
        $today = Carbon::today();
        $dayOfMonth = (int) $today->format('j');

        $due = RecurringExpense::where('is_active', true)
            ->where('day_of_month', $dayOfMonth)
            ->where(function ($query) use ($today) {
                $query->whereNull('last_logged_at')
                    ->orWhere('last_logged_at', '<', $today->startOfDay()->toDateTimeString());
            })
            ->get();

        foreach ($due as $recurring) {
            Expense::create([
                'user_id'     => $recurring->user_id,
                'amount'      => $recurring->amount,
                'description' => $recurring->description . ' (Auto)',
                'category'    => $recurring->category,
                'date'        => $today,
            ]);

            $recurring->update(['last_logged_at' => $today]);
        }

        $count = $due->count();
        $this->info("Logged {$count} recurring expense(s).");

        return Command::SUCCESS;
    }
}
