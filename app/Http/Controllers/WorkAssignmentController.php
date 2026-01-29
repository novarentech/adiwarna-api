<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkAssignmentRequest;
use App\Http\Requests\UpdateWorkAssignmentRequest;
use App\Http\Resources\WorkAssignmentListResource;
use App\Http\Resources\WorkAssignmentResource;
use App\Models\WorkAssignment;
use Illuminate\Http\JsonResponse;

class WorkAssignmentController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(): JsonResponse
    {
        $query = WorkAssignment::with(['customer', 'customerLocation']);

        if ($search = request('search')) {
            $query->search($search);
        }

        $query->sortByDefault(request('sort_order', 'desc'));

        $workAssignments = $query->paginate(request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => WorkAssignmentListResource::collection($workAssignments),
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
        $workAssignment = WorkAssignment::createWithWorkers($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Work Assignment created successfully',
            'data' => new WorkAssignmentResource($workAssignment),
        ], 201);
    }

    public function show(WorkAssignment $workAssignment): JsonResponse
    {
        $workAssignment->load(['customer', 'workers']);

        return response()->json([
            'success' => true,
            'data' => new WorkAssignmentResource($workAssignment),
        ]);
    }

    public function update(UpdateWorkAssignmentRequest $request, WorkAssignment $workAssignment): JsonResponse
    {
        $workAssignment = $workAssignment->updateWithWorkers($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Work Assignment updated successfully',
            'data' => new WorkAssignmentResource($workAssignment),
        ]);
    }

    public function destroy(WorkAssignment $workAssignment): JsonResponse
    {
        $workAssignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work Assignment deleted successfully',
        ]);
    }
}

