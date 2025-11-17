<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWorkAssignmentRequest;
use App\Http\Requests\Api\V1\UpdateWorkAssignmentRequest;
use App\Http\Resources\V1\WorkAssignmentCollection;
use App\Http\Resources\V1\WorkAssignmentResource;
use App\Models\WorkAssignment;
use App\Services\WorkAssignmentService;
use Illuminate\Http\JsonResponse;

class WorkAssignmentController extends Controller
{
    public function __construct(
        private readonly WorkAssignmentService $workAssignmentService
    ) {
        $this->authorizeResource(WorkAssignment::class, 'work_assignment');
    }

    public function index(): JsonResponse
    {
        $workAssignments = $this->workAssignmentService->getPaginatedWorkAssignments(
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => new WorkAssignmentCollection($workAssignments),
            'meta' => [
                'current_page' => $workAssignments->currentPage(),
                'last_page' => $workAssignments->lastPage(),
                'per_page' => $workAssignments->perPage(),
                'total' => $workAssignments->total(),
            ],
        ]);
    }

    public function store(StoreWorkAssignmentRequest $request): JsonResponse
    {
        $workAssignment = $this->workAssignmentService->createWorkAssignment($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Work Assignment created successfully',
            'data' => new WorkAssignmentResource($workAssignment),
        ], 201);
    }

    public function show(WorkAssignment $workAssignment): JsonResponse
    {
        $workAssignment->load(['customer', 'employees']);

        return response()->json([
            'success' => true,
            'data' => new WorkAssignmentResource($workAssignment),
        ]);
    }

    public function update(UpdateWorkAssignmentRequest $request, WorkAssignment $workAssignment): JsonResponse
    {
        $workAssignment = $this->workAssignmentService->updateWorkAssignment(
            $workAssignment->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Work Assignment updated successfully',
            'data' => new WorkAssignmentResource($workAssignment),
        ]);
    }

    public function destroy(WorkAssignment $workAssignment): JsonResponse
    {
        $this->workAssignmentService->deleteWorkAssignment($workAssignment->id);

        return response()->json([
            'success' => true,
            'message' => 'Work Assignment deleted successfully',
        ]);
    }
}
