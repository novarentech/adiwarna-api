<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentProjectRequest;
use App\Http\Requests\UpdateEquipmentProjectRequest;
use App\Http\Resources\EquipmentProjectListResource;
use App\Http\Resources\EquipmentProjectResource;
use App\Models\EquipmentProject;
use Illuminate\Http\JsonResponse;

class EquipmentProjectController extends Controller
{
    /**
     * Display a listing of project equipment.
     * Supports search by customer name, location, prepared by, or verified by.
     */
    public function index(): JsonResponse
    {
        //

        $query = EquipmentProject::with(['customer', 'customerLocation']);

        if ($search = request('search')) {
            $query->search($search);
        }

        $equipment = $query->paginate(request('per_page', 15));

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
        //

        $equipment = EquipmentProject::createWithEquipments($request->validated());

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
        //

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
        //

        $equipment = $equipmentProject->updateWithEquipments($request->validated());

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
        //

        $equipmentProject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project equipment deleted successfully',
        ]);
    }
}

