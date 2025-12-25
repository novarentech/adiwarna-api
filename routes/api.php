<?php

use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DailyActivityController;
use App\Http\Controllers\Api\V1\DeliveryNoteController;
use App\Http\Controllers\Api\V1\DocumentTransmittalController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\EquipmentGeneralController;
use App\Http\Controllers\Api\V1\EquipmentProjectController;
use App\Http\Controllers\Api\V1\MaterialReceivingReportController;
use App\Http\Controllers\Api\V1\OperationalController;
use App\Http\Controllers\Api\V1\PayrollEmployeeController;
use App\Http\Controllers\Api\V1\PayrollPeriodController;
use App\Http\Controllers\Api\V1\PayrollProjectController;
use App\Http\Controllers\Api\V1\PayrollSlipController;
use App\Http\Controllers\Api\V1\PayrollTimesheetController;
use App\Http\Controllers\Api\V1\PurchaseOrderController;
use App\Http\Controllers\Api\V1\PurchaseRequisitionController;
use App\Http\Controllers\Api\V1\QuotationController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\TrackRecordController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WorkAssignmentController;
use App\Http\Controllers\Api\V1\WorkOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('throttle:api')->group(function () {
    // Authentication routes (public)
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Authentication
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // User Management (admin only)
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Customers
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::get('/customers/{customer}', [CustomerController::class, 'show']);
        Route::put('/customers/{customer}', [CustomerController::class, 'update']);
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

        // Employees
        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
        Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

        // Quotations
        Route::get('/quotations', [QuotationController::class, 'index']);
        Route::post('/quotations', [QuotationController::class, 'store']);
        Route::get('/quotations/{quotation}', [QuotationController::class, 'show']);
        Route::put('/quotations/{quotation}', [QuotationController::class, 'update']);
        Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy']);

        // Purchase Orders
        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store']);
        Route::get('/purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'show']);
        Route::put('/purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'update']);
        Route::delete('/purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'destroy']);

        // Work Assignments
        Route::get('/work-assignments', [WorkAssignmentController::class, 'index']);
        Route::post('/work-assignments', [WorkAssignmentController::class, 'store']);
        Route::get('/work-assignments/{work_assignment}', [WorkAssignmentController::class, 'show']);
        Route::put('/work-assignments/{work_assignment}', [WorkAssignmentController::class, 'update']);
        Route::delete('/work-assignments/{work_assignment}', [WorkAssignmentController::class, 'destroy']);

        // Daily Activities
        Route::get('/daily-activities', [DailyActivityController::class, 'index']);
        Route::post('/daily-activities', [DailyActivityController::class, 'store']);
        Route::get('/daily-activities/{daily_activity}', [DailyActivityController::class, 'show']);
        Route::put('/daily-activities/{daily_activity}', [DailyActivityController::class, 'update']);
        Route::delete('/daily-activities/{daily_activity}', [DailyActivityController::class, 'destroy']);

        // Payroll Projects
        Route::get('/payroll/projects', [PayrollProjectController::class, 'index']);
        Route::post('/payroll/projects', [PayrollProjectController::class, 'store']);
        Route::get('/payroll/projects/{payroll_project}', [PayrollProjectController::class, 'show']);
        Route::put('/payroll/projects/{payroll_project}', [PayrollProjectController::class, 'update']);
        Route::delete('/payroll/projects/{payroll_project}', [PayrollProjectController::class, 'destroy']);

        // Payroll Periods (nested under projects)
        Route::get('/payroll/projects/{projectId}/periods', [PayrollPeriodController::class, 'index']);
        Route::post('/payroll/projects/{projectId}/periods', [PayrollPeriodController::class, 'store']);
        Route::get('/payroll/projects/{projectId}/periods/{payroll_period}', [PayrollPeriodController::class, 'show']);
        Route::put('/payroll/projects/{projectId}/periods/{payroll_period}', [PayrollPeriodController::class, 'update']);
        Route::delete('/payroll/projects/{projectId}/periods/{payroll_period}', [PayrollPeriodController::class, 'destroy']);

        // Payroll Employees (nested under periods)
        Route::get('/payroll/periods/{periodId}/employees', [PayrollEmployeeController::class, 'index']);
        Route::post('/payroll/periods/{periodId}/employees', [PayrollEmployeeController::class, 'store']);
        Route::get('/payroll/periods/{periodId}/employees/{payroll_employee}', [PayrollEmployeeController::class, 'show']);
        Route::put('/payroll/periods/{periodId}/employees/{payroll_employee}', [PayrollEmployeeController::class, 'update']);
        Route::delete('/payroll/periods/{periodId}/employees/{payroll_employee}', [PayrollEmployeeController::class, 'destroy']);
        Route::post('/payroll/periods/{periodId}/employees/{payroll_employee}/recalculate', [PayrollEmployeeController::class, 'recalculate']);

        // Payroll Timesheets (nested under employees)
        Route::get('/payroll/employees/{employeeId}/timesheets', [PayrollTimesheetController::class, 'index']);
        Route::post('/payroll/employees/{employeeId}/timesheets', [PayrollTimesheetController::class, 'store']);
        Route::put('/payroll/employees/{employeeId}/timesheets/{date}', [PayrollTimesheetController::class, 'update']);
        Route::delete('/payroll/employees/{employeeId}/timesheets/{date}', [PayrollTimesheetController::class, 'destroy']);
        Route::post('/payroll/employees/{employeeId}/timesheets/bulk', [PayrollTimesheetController::class, 'bulkUpdate']);

        // Payroll Slips (nested under periods)
        Route::post('/payroll/periods/{periodId}/slips/generate', [PayrollSlipController::class, 'generate']);
        Route::get('/payroll/periods/{periodId}/slips', [PayrollSlipController::class, 'index']);
        Route::get('/payroll/slips/{id}', [PayrollSlipController::class, 'show']);
        Route::delete('/payroll/slips/{id}', [PayrollSlipController::class, 'destroy']);

        // Schedules
        Route::get('/schedules', [ScheduleController::class, 'index']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

        // Work Orders
        Route::get('/work-orders', [WorkOrderController::class, 'index']);
        Route::post('/work-orders', [WorkOrderController::class, 'store']);
        Route::get('/work-orders/{work_order}', [WorkOrderController::class, 'show']);
        Route::put('/work-orders/{work_order}', [WorkOrderController::class, 'update']);
        Route::delete('/work-orders/{work_order}', [WorkOrderController::class, 'destroy']);

        // Document Transmittals
        Route::get('/document-transmittals', [DocumentTransmittalController::class, 'index']);
        Route::post('/document-transmittals', [DocumentTransmittalController::class, 'store']);
        Route::get('/document-transmittals/{document_transmittal}', [DocumentTransmittalController::class, 'show']);
        Route::put('/document-transmittals/{document_transmittal}', [DocumentTransmittalController::class, 'update']);
        Route::delete('/document-transmittals/{document_transmittal}', [DocumentTransmittalController::class, 'destroy']);

        // Purchase Requisitions
        Route::get('/purchase-requisitions', [PurchaseRequisitionController::class, 'index']);
        Route::post('/purchase-requisitions', [PurchaseRequisitionController::class, 'store']);
        Route::get('/purchase-requisitions/{purchase_requisition}', [PurchaseRequisitionController::class, 'show']);
        Route::put('/purchase-requisitions/{purchase_requisition}', [PurchaseRequisitionController::class, 'update']);
        Route::delete('/purchase-requisitions/{purchase_requisition}', [PurchaseRequisitionController::class, 'destroy']);

        // Material Receiving Reports
        Route::get('/material-receiving-reports', [MaterialReceivingReportController::class, 'index']);
        Route::post('/material-receiving-reports', [MaterialReceivingReportController::class, 'store']);
        Route::get('/material-receiving-reports/{material_receiving_report}', [MaterialReceivingReportController::class, 'show']);
        Route::put('/material-receiving-reports/{material_receiving_report}', [MaterialReceivingReportController::class, 'update']);
        Route::delete('/material-receiving-reports/{material_receiving_report}', [MaterialReceivingReportController::class, 'destroy']);

        // Delivery Notes
        Route::get('/delivery-notes', [DeliveryNoteController::class, 'index']);
        Route::post('/delivery-notes', [DeliveryNoteController::class, 'store']);
        Route::get('/delivery-notes/{delivery_note}', [DeliveryNoteController::class, 'show']);
        Route::put('/delivery-notes/{delivery_note}', [DeliveryNoteController::class, 'update']);
        Route::delete('/delivery-notes/{delivery_note}', [DeliveryNoteController::class, 'destroy']);

        // Equipment General
        Route::get('/equipment/general', [EquipmentGeneralController::class, 'index']);
        Route::post('/equipment/general', [EquipmentGeneralController::class, 'store']);
        Route::get('/equipment/general/{equipment_general}', [EquipmentGeneralController::class, 'show']);
        Route::put('/equipment/general/{equipment_general}', [EquipmentGeneralController::class, 'update']);
        Route::delete('/equipment/general/{equipment_general}', [EquipmentGeneralController::class, 'destroy']);

        // Equipment Project
        Route::get('/equipment/project', [EquipmentProjectController::class, 'index']);
        Route::post('/equipment/project', [EquipmentProjectController::class, 'store']);
        Route::get('/equipment/project/{equipment_project}', [EquipmentProjectController::class, 'show']);
        Route::put('/equipment/project/{equipment_project}', [EquipmentProjectController::class, 'update']);
        Route::delete('/equipment/project/{equipment_project}', [EquipmentProjectController::class, 'destroy']);

        // Track Records (Read-only - displays work orders history)
        Route::get('/track-records', [TrackRecordController::class, 'index']);

        // Operational
        Route::get('/operational', [OperationalController::class, 'index']);
        Route::post('/operational', [OperationalController::class, 'store']);
        Route::get('/operational/{operational}', [OperationalController::class, 'show']);
        Route::put('/operational/{operational}', [OperationalController::class, 'update']);
        Route::delete('/operational/{operational}', [OperationalController::class, 'destroy']);

        // About (Company Information)
        Route::get('/about', [AboutController::class, 'index']);
        Route::post('/about', [AboutController::class, 'store']);
        Route::get('/about/active', [AboutController::class, 'active']);
        Route::get('/about/{about}', [AboutController::class, 'show']);
        Route::put('/about/{about}', [AboutController::class, 'update']);
        Route::delete('/about/{about}', [AboutController::class, 'destroy']);
    });
});
