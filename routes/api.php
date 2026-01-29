<?php

use App\Http\Controllers\{
    AuthController,
    CustomerController,
    DeliveryNoteController,
    DocumentTransmittalController,
    EmployeeController,
    EquipmentGeneralController,
    EquipmentProjectController,
    MaterialReceivingReportController,
    PurchaseOrderController,
    PurchaseRequisitionController,
    QuotationController,
    TrackRecordController,
    UserController,
    WorkAssignmentController,
    WorkOrderController
};

use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Standard API Resources
    Route::apiResources([
        'users' => UserController::class,
        'customers' => CustomerController::class,
        'employees' => EmployeeController::class,
        'quotations' => QuotationController::class,
        'purchase-orders' => PurchaseOrderController::class,
        'work-assignments' => WorkAssignmentController::class,
        'work-orders' => WorkOrderController::class,
        'document-transmittals' => DocumentTransmittalController::class,
        'purchase-requisitions' => PurchaseRequisitionController::class,
        'material-receiving-reports' => MaterialReceivingReportController::class,
        'delivery-notes' => DeliveryNoteController::class,
    ]);

    // Equipment Resources
    Route::prefix('equipment')->group(function () {
        Route::apiResource('general', EquipmentGeneralController::class)->parameters(['general' => 'equipment_general']);
        Route::apiResource('project', EquipmentProjectController::class)->parameters(['project' => 'equipment_project']);
    });

    // Track Records (Read-only)
    Route::get('/track-records', [TrackRecordController::class, 'index']);
});
