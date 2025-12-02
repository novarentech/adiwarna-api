<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePayrollPeriodRequest;
use App\Http\Requests\Api\V1\UpdatePayrollPeriodRequest;
use App\Http\Resources\V1\PayrollPeriodResource;
use App\Http\Resources\V1\PayrollPeriodListResource;
use App\Models\PayrollPeriod;
use App\Services\PayrollPeriodService;
use Illuminate\Http\JsonResponse;

class PayrollPeriodController extends Controller
{
    public function __construct(
        private readonly PayrollPeriodService $payrollPeriodService
    ) {
        $this->authorizeResource(PayrollPeriod::class, 'payroll_period');
    }

    public function index(int $projectId): JsonResponse
    {
        $periods = $this->payrollPeriodService->getPaginatedPeriods(
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => PayrollPeriodListResource::collection($periods),
            'meta' => [
                'current_page' => $periods->currentPage(),
                'last_page' => $periods->lastPage(),
                'per_page' => $periods->perPage(),
                'total' => $periods->total(),
            ],
        ]);
    }

    public function store(StorePayrollPeriodRequest $request, int $projectId): JsonResponse
    {
        $data = array_merge($request->validated(), ['payroll_project_id' => $projectId]);
        $period = $this->payrollPeriodService->createPeriod($data);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Period created successfully',
            'data' => new PayrollPeriodResource($period),
        ], 201);
    }

    public function show(int $projectId, PayrollPeriod $payrollPeriod): JsonResponse
    {
        $payrollPeriod->load('employees');

        return response()->json([
            'success' => true,
            'data' => new PayrollPeriodResource($payrollPeriod),
        ]);
    }

    public function update(UpdatePayrollPeriodRequest $request, int $projectId, PayrollPeriod $payrollPeriod): JsonResponse
    {
        $period = $this->payrollPeriodService->updatePeriod($payrollPeriod->id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payroll Period updated successfully',
            'data' => new PayrollPeriodResource($period),
        ]);
    }

    public function destroy(int $projectId, PayrollPeriod $payrollPeriod): JsonResponse
    {
        $this->payrollPeriodService->deletePeriod($payrollPeriod->id);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Period deleted successfully',
        ]);
    }
}
