<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register repositories
        $this->app->bind(
            \App\Contracts\Repositories\CustomerRepositoryInterface::class,
            \App\Repositories\CustomerRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\EmployeeRepositoryInterface::class,
            \App\Repositories\EmployeeRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\QuotationRepositoryInterface::class,
            \App\Repositories\QuotationRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\PurchaseOrderRepositoryInterface::class,
            \App\Repositories\PurchaseOrderRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\WorkAssignmentRepositoryInterface::class,
            \App\Repositories\WorkAssignmentRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\DailyActivityRepositoryInterface::class,
            \App\Repositories\DailyActivityRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\PayrollProjectRepositoryInterface::class,
            \App\Repositories\PayrollProjectRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\PayrollPeriodRepositoryInterface::class,
            \App\Repositories\PayrollPeriodRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\PayrollEmployeeRepositoryInterface::class,
            \App\Repositories\PayrollEmployeeRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
