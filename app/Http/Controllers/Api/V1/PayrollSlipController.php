<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GeneratePayrollSlipRequest;
use App\Http\Resources\V1\PayrollSlipResource;
use App\Models\PayrollSlip;
use App\Services\PayrollSlipService;
use Illuminate\Http\JsonResponse;

class PayrollSlipController extends Controller
{
    public function __construct(
        private readonly PayrollSlipService $payrollSlipService
    ) {}

    /**
     * Generate payroll slips for a period.
     */
    public function generate(GeneratePayrollSlipRequest $request, int $periodId): JsonResponse
    {
        $this->authorize('create', PayrollSlip::class);

        $slips = $this->payrollSlipService->generateSlips(
            periodId: $periodId,
            employeeId: $request->input('employee_id'),
            slipType: $request->input('slip_type')
        );

        return response()->json([
            'success' => true,
            'message' => 'Payroll slips generated successfully',
            'data' => PayrollSlipResource::collection($slips),
        ], 201);
    }

    /**
     * Display a listing of payroll slips for a period.
     */
    public function index(int $periodId): JsonResponse
    {
        $this->authorize('viewAny', PayrollSlip::class);

        $slips = $this->payrollSlipService->getPaginatedSlips(
            periodId: $periodId,
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => PayrollSlipResource::collection($slips),
            'meta' => [
                'current_page' => $slips->currentPage(),
                'last_page' => $slips->lastPage(),
                'per_page' => $slips->perPage(),
                'total' => $slips->total(),
            ],
        ]);
    }

    /**
     * Display the specified payroll slip.
     */
    public function show(int $id): JsonResponse
    {
        $slip = $this->payrollSlipService->getSlipById($id);

        if (!$slip) {
            return response()->json([
                'success' => false,
                'message' => 'Payroll slip not found',
            ], 404);
        }

        $this->authorize('view', $slip);

        return response()->json([
            'success' => true,
            'data' => new PayrollSlipResource($slip),
        ]);
    }

    /**
     * Remove the specified payroll slip.
     */
    public function destroy(int $id): JsonResponse
    {
        $slip = $this->payrollSlipService->getSlipById($id);

        if (!$slip) {
            return response()->json([
                'success' => false,
                'message' => 'Payroll slip not found',
            ], 404);
        }

        $this->authorize('delete', $slip);

        $this->payrollSlipService->deleteSlip($id);

        return response()->json([
            'success' => true,
            'message' => 'Payroll slip deleted successfully',
        ]);
    }
}
