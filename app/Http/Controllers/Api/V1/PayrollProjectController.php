<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePayrollProjectRequest;
use App\Http\Requests\Api\V1\UpdatePayrollProjectRequest;
use App\Http\Resources\V1\PayrollProjectResource;
use App\Models\PayrollProject;
use App\Services\PayrollProjectService;
use Illuminate\Http\JsonResponse;

class PayrollProjectController extends Controller
{
    public function __construct(
        private readonly PayrollProjectService $payrollProjectService
    ) {
        $this->authorizeResource(PayrollProject::class, 'payroll_project');
    }

    public function index(): JsonResponse
    {
        $projects = $this->payrollProjectService->getPaginatedProjects(perPage: request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => PayrollProjectResource::collection($projects),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
            ],
        ]);
    }

    public function store(StorePayrollProjectRequest $request): JsonResponse
    {
        $project = $this->payrollProjectService->createProject($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payroll Project created successfully',
            'data' => new PayrollProjectResource($project),
        ], 201);
    }

    public function show(PayrollProject $payrollProject): JsonResponse
    {
        $payrollProject->load('periods');

        return response()->json([
            'success' => true,
            'data' => new PayrollProjectResource($payrollProject),
        ]);
    }

    public function update(UpdatePayrollProjectRequest $request, PayrollProject $payrollProject): JsonResponse
    {
        $project = $this->payrollProjectService->updateProject($payrollProject->id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payroll Project updated successfully',
            'data' => new PayrollProjectResource($project),
        ]);
    }

    public function destroy(PayrollProject $payrollProject): JsonResponse
    {
        $this->payrollProjectService->deleteProject($payrollProject->id);

        return response()->json([
            'success' => true,
            'message' => 'Payroll Project deleted successfully',
        ]);
    }
}
