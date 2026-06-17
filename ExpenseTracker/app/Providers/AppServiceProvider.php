<?php

namespace App\Providers;

use App\Services\BudgetCoachService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BudgetCoachService::class);
    }

    public function boot(): void
    {
        //
    }
}
