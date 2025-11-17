<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DailyActivityController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\PurchaseOrderController;
use App\Http\Controllers\Api\V1\QuotationController;
use App\Http\Controllers\Api\V1\WorkAssignmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Authentication routes (public)
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Authentication
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

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
    });
});
