<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Master Data
        \App\Models\Customer::class => \App\Policies\CustomerPolicy::class,
        \App\Models\Employee::class => \App\Policies\EmployeePolicy::class,

        // Sales Modules
        \App\Models\Quotation::class => \App\Policies\QuotationPolicy::class,
        \App\Models\PurchaseOrder::class => \App\Policies\PurchaseOrderPolicy::class,

        // Operations Modules
        \App\Models\WorkAssignment::class => \App\Policies\WorkAssignmentPolicy::class,
        \App\Models\DailyActivity::class => \App\Policies\DailyActivityPolicy::class,
        \App\Models\Schedule::class => \App\Policies\SchedulePolicy::class,
        \App\Models\WorkOrder::class => \App\Policies\WorkOrderPolicy::class,

        // Document Modules
        \App\Models\DocumentTransmittal::class => \App\Policies\DocumentTransmittalPolicy::class,
        \App\Models\PurchaseRequisition::class => \App\Policies\PurchaseRequisitionPolicy::class,
        \App\Models\MaterialReceivingReport::class => \App\Policies\MaterialReceivingReportPolicy::class,

        // Equipment & System Modules
        \App\Models\EquipmentGeneral::class => \App\Policies\EquipmentPolicy::class,
        \App\Models\EquipmentProject::class => \App\Policies\EquipmentPolicy::class,
        \App\Models\TrackRecord::class => \App\Policies\TrackRecordPolicy::class,
        \App\Models\Operational::class => \App\Policies\OperationalPolicy::class,
        \App\Models\About::class => \App\Policies\AboutPolicy::class,

        // Payroll Modules
        \App\Models\PayrollProject::class => \App\Policies\PayrollProjectPolicy::class,
        \App\Models\PayrollPeriod::class => \App\Policies\PayrollPeriodPolicy::class,
        \App\Models\PayrollEmployee::class => \App\Policies\PayrollEmployeePolicy::class,
        \App\Models\PayrollTimesheet::class => \App\Policies\PayrollTimesheetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
