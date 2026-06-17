<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Demo user ──────────────────────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        // ── 30 expenses spread over the last 60 days ───────────────────────
        $descriptions = [
            'food'           => ['Lunch at café', 'Grocery run', 'Dinner with friends', 'Street food', 'Coffee & snacks'],
            'transportation' => ['Uber ride', 'Monthly bus pass', 'Petrol fill-up', 'Auto fare', 'Train ticket'],
            'entertainment'  => ['Netflix subscription', 'Movie tickets', 'Concert entry', 'Gaming purchase', 'Book purchase'],
            'health'         => ['Pharmacy', 'Doctor consultation', 'Gym visit', 'Vitamins', 'Health checkup'],
            'shopping'       => ['Clothes shopping', 'Electronics store', 'Online order', 'Shoes', 'Home décor'],
            'utilities'      => ['Electricity bill', 'Internet bill', 'Water bill', 'Gas cylinder', 'Mobile recharge'],
            'other'          => ['Miscellaneous', 'Charity donation', 'Gift for friend', 'Stationery', 'Parking fee'],
        ];

        $categories = Expense::CATEGORIES;

        // Weight categories so data looks realistic
        $weightedCategories = array_merge(
            array_fill(0, 8, 'food'),
            array_fill(0, 5, 'transportation'),
            array_fill(0, 4, 'shopping'),
            array_fill(0, 4, 'utilities'),
            array_fill(0, 3, 'entertainment'),
            array_fill(0, 3, 'health'),
            array_fill(0, 3, 'other')
        );

        for ($i = 0; $i < 30; $i++) {
            $category    = $weightedCategories[array_rand($weightedCategories)];
            $descOptions = $descriptions[$category];
            $daysAgo     = rand(0, 60);

            Expense::create([
                'user_id'     => $user->id,
                'amount'      => fake()->randomFloat(2, 10, 500),
                'description' => $descOptions[array_rand($descOptions)],
                'category'    => $category,
                'date'        => Carbon::today()->subDays($daysAgo),
            ]);
        }

        // ── 3 recurring expenses ───────────────────────────────────────────
        $recurring = [
            [
                'description'  => 'Netflix',
                'amount'       => 649.00,
                'category'     => 'entertainment',
                'day_of_month' => 1,
                'is_active'    => true,
            ],
            [
                'description'  => 'Rent',
                'amount'       => 12000.00,
                'category'     => 'utilities',
                'day_of_month' => 5,
                'is_active'    => true,
            ],
            [
                'description'  => 'Gym membership',
                'amount'       => 1500.00,
                'category'     => 'health',
                'day_of_month' => 10,
                'is_active'    => true,
            ],
        ];

        foreach ($recurring as $data) {
            RecurringExpense::firstOrCreate(
                ['user_id' => $user->id, 'description' => $data['description']],
                array_merge($data, ['user_id' => $user->id])
            );
        }

        $this->command->info('✅  Demo user seeded: demo@example.com / password');
        $this->command->info('✅  30 expenses created across the last 60 days');
        $this->command->info('✅  3 recurring expenses: Netflix, Rent, Gym');
    }
}
