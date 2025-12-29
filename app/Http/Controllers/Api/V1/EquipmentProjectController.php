<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEquipmentProjectRequest;
use App\Http\Requests\Api\V1\UpdateEquipmentProjectRequest;
use App\Http\Resources\V1\EquipmentProjectResource;
use App\Http\Resources\V1\EquipmentProjectListResource;
use App\Models\EquipmentProject;
use App\Services\EquipmentService;
use Illuminate\Http\JsonResponse;

class EquipmentProjectController extends Controller
{
    public function __construct(
        private readonly EquipmentService $equipmentService
    ) {}

    /**
     * Display a listing of project equipment.
     * Supports search by customer name, location, prepared by, or verified by.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', EquipmentProject::class);

        $equipment = $this->equipmentService->getPaginatedProject(
            perPage: request('per_page', 15),
            search: request('search')
        );

        return response()->json([
            'success' => true,
            'data' => EquipmentProjectListResource::collection($equipment),
            'meta' => [
                'current_page' => $equipment->currentPage(),
                'last_page' => $equipment->lastPage(),
                'per_page' => $equipment->perPage(),
                'total' => $equipment->total(),
            ],
        ]);
    }

    /**
     * Store a newly created project equipment.
     */
    public function store(StoreEquipmentProjectRequest $request): JsonResponse
    {
        $this->authorize('create', EquipmentProject::class);

        $equipment = $this->equipmentService->createProject($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project equipment created successfully',
            'data' => new EquipmentProjectResource($equipment),
        ], 201);
    }

    /**
     * Display the specified project equipment.
     */
    public function show(EquipmentProject $equipmentProject): JsonResponse
    {
        $this->authorize('viewProject', [EquipmentProject::class, $equipmentProject]);

        $equipmentProject->load(['customer', 'customerLocation', 'equipments']);

        return response()->json([
            'success' => true,
            'data' => new EquipmentProjectResource($equipmentProject),
        ]);
    }

    /**
     * Update the specified project equipment.
     */
    public function update(UpdateEquipmentProjectRequest $request, EquipmentProject $equipmentProject): JsonResponse
    {
        $this->authorize('updateProject', [EquipmentProject::class, $equipmentProject]);

        $equipment = $this->equipmentService->updateProject(
            $equipmentProject->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Project equipment updated successfully',
            'data' => new EquipmentProjectResource($equipment),
        ]);
    }

    /**
     * Remove the specified project equipment.
     */
    public function destroy(EquipmentProject $equipmentProject): JsonResponse
    {
        $this->authorize('deleteProject', [EquipmentProject::class, $equipmentProject]);

        $this->equipmentService->deleteProject($equipmentProject->id);

        return response()->json([
            'success' => true,
            'message' => 'Project equipment deleted successfully',
        ]);
    }
}
