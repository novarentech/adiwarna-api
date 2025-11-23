<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\UpdateEmployeeRequest;
use App\Http\Resources\V1\EmployeeCollection;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $employeeService
    ) {
        $this->authorizeResource(Employee::class, 'employee');
    }

    /**
     * Display a listing of employees.
     */
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getPaginatedEmployees(
            perPage: request('per_page', 15),
            search: request('search')
        );

        return response()->json([
            'success' => true,
            'data' => new EmployeeCollection($employees),
            'meta' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    /**
     * Store a newly created employee.
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
            'data' => new EmployeeResource($employee),
        ], 201);
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Update the specified employee.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee = $this->employeeService->updateEmployee($employee->id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
            'data' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->employeeService->deleteEmployee($employee->id);

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully',
        ]);
    }
}
