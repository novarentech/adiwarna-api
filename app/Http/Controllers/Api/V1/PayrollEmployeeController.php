<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePayrollEmployeeRequest;
use App\Http\Requests\Api\V1\UpdatePayrollEmployeeRequest;
use App\Http\Resources\V1\PayrollEmployeeResource;
use App\Models\PayrollEmployee;
use App\Services\PayrollEmployeeService;
use Illuminate\Http\JsonResponse;

class PayrollEmployeeController extends Controller
{
    public function __construct(
        private readonly PayrollEmployeeService $payrollEmployeeService
    ) {
        $this->authorizeResource(PayrollEmployee::class, 'payroll_employee');
    }

    public function index(int $periodId): JsonResponse
    {
        $employees = $this->payrollEmployeeService->getPaginatedEmployees(perPage: request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => PayrollEmployeeResource::collection($employees),
        ]);
    }

    public function store(StorePayrollEmployeeRequest $request, int $periodId): JsonResponse
    {
        $data = array_merge($request->validated(), ['payroll_period_id' => $periodId]);
        $employee = $this->payrollEmployeeService->createEmployee($data);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Employee created successfully',
            'data' => new PayrollEmployeeResource($employee),
        ], 201);
    }

    public function show(int $periodId, PayrollEmployee $payrollEmployee): JsonResponse
    {
        $payrollEmployee->load('timesheets');

        return response()->json([
            'success' => true,
            'data' => new PayrollEmployeeResource($payrollEmployee),
        ]);
    }

    public function update(UpdatePayrollEmployeeRequest $request, int $periodId, PayrollEmployee $payrollEmployee): JsonResponse
    {
        $employee = $this->payrollEmployeeService->updateEmployee($payrollEmployee->id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payroll Employee updated successfully',
            'data' => new PayrollEmployeeResource($employee),
        ]);
    }

    public function destroy(int $periodId, PayrollEmployee $payrollEmployee): JsonResponse
    {
        $this->payrollEmployeeService->deleteEmployee($payrollEmployee->id);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Employee deleted successfully',
        ]);
    }

    public function recalculate(int $periodId, PayrollEmployee $payrollEmployee): JsonResponse
    {
        $employee = $this->payrollEmployeeService->recalculateTotals($payrollEmployee->id);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Employee recalculated successfully',
            'data' => new PayrollEmployeeResource($employee),
        ]);
    }
}
