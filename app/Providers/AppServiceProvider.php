<?php

namespace App\Providers;

use App\Contract\AssignmentStrategy;
use App\Contract\DeveloperSelectionStrategy;
use App\Services\Scheduling\Strategies\EfficiencyBasedSelectionStrategy;
use App\Services\Scheduling\Strategies\GreedyAssignmentStrategy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AssignmentStrategy::class, GreedyAssignmentStrategy::class);
        $this->app->bind(DeveloperSelectionStrategy::class, EfficiencyBasedSelectionStrategy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
