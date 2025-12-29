<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDeliveryNoteRequest;
use App\Http\Requests\Api\V1\UpdateDeliveryNoteRequest;
use App\Http\Resources\V1\DeliveryNoteResource;
use App\Http\Resources\V1\DeliveryNoteListResource;
use App\Models\DeliveryNote;
use App\Services\DeliveryNoteService;
use Illuminate\Http\JsonResponse;

class DeliveryNoteController extends Controller
{
    public function __construct(
        private readonly DeliveryNoteService $deliveryNoteService
    ) {
        $this->authorizeResource(DeliveryNote::class, 'delivery_note');
    }

    /**
     * Display a listing of delivery notes.
     */
    public function index(): JsonResponse
    {
        $deliveryNotes = $this->deliveryNoteService->getPaginatedDeliveryNotes(
            perPage: request('per_page', 15),
            search: request('search')
        );

        return response()->json([
            'success' => true,
            'data' => DeliveryNoteListResource::collection($deliveryNotes),
            'meta' => [
                'current_page' => $deliveryNotes->currentPage(),
                'last_page' => $deliveryNotes->lastPage(),
                'per_page' => $deliveryNotes->perPage(),
                'total' => $deliveryNotes->total(),
            ],
        ]);
    }

    /**
     * Store a newly created delivery note.
     */
    public function store(StoreDeliveryNoteRequest $request): JsonResponse
    {
        $deliveryNote = $this->deliveryNoteService->createDeliveryNoteWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Delivery note created successfully',
            'data' => new DeliveryNoteResource($deliveryNote),
        ], 201);
    }

    /**
     * Display the specified delivery note.
     */
    public function show(DeliveryNote $deliveryNote): JsonResponse
    {
        $deliveryNote->load('items');

        return response()->json([
            'success' => true,
            'data' => new DeliveryNoteResource($deliveryNote),
        ]);
    }

    /**
     * Update the specified delivery note.
     */
    public function update(UpdateDeliveryNoteRequest $request, DeliveryNote $deliveryNote): JsonResponse
    {
        $deliveryNote = $this->deliveryNoteService->updateDeliveryNoteWithItems(
            $deliveryNote->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Delivery note updated successfully',
            'data' => new DeliveryNoteResource($deliveryNote),
        ]);
    }

    /**
     * Remove the specified delivery note.
     */
    public function destroy(DeliveryNote $deliveryNote): JsonResponse
    {
        $this->deliveryNoteService->deleteDeliveryNote($deliveryNote->id);

        return response()->json([
            'success' => true,
            'message' => 'Delivery note deleted successfully',
        ]);
    }
}
