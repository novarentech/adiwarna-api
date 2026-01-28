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
        Scramble::ignoreDefaultRoutes();
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
            \App\Contracts\Repositories\ScheduleRepositoryInterface::class,
            \App\Repositories\ScheduleRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\WorkOrderRepositoryInterface::class,
            \App\Repositories\WorkOrderRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\DocumentTransmittalRepositoryInterface::class,
            \App\Repositories\DocumentTransmittalRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\TransmittalDocumentRepositoryInterface::class,
            \App\Repositories\TransmittalDocumentRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\EquipmentGeneralRepositoryInterface::class,
            \App\Repositories\EquipmentGeneralRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\EquipmentProjectRepositoryInterface::class,
            \App\Repositories\EquipmentProjectRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\DeliveryNoteRepositoryInterface::class,
            \App\Repositories\DeliveryNoteRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\DeliveryNoteItemRepositoryInterface::class,
            \App\Repositories\DeliveryNoteItemRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\PurchaseRequisitionRepositoryInterface::class,
            \App\Repositories\PurchaseRequisitionRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\PurchaseRequisitionItemRepositoryInterface::class,
            \App\Repositories\PurchaseRequisitionItemRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\MaterialReceivingReportItemRepositoryInterface::class,
            \App\Repositories\MaterialReceivingReportItemRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\MaterialReceivingReportRepositoryInterface::class,
            \App\Repositories\MaterialReceivingReportRepository::class
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

        $this->app->bind(
            \App\Contracts\Repositories\AboutRepositoryInterface::class,
            \App\Repositories\AboutRepository::class
        );

        $this->app->bind(
            \App\Contracts\Repositories\OperationalRepositoryInterface::class,
            \App\Repositories\OperationalRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
    }
}
