<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliveryNoteRequest;
use App\Http\Requests\UpdateDeliveryNoteRequest;
use App\Http\Resources\DeliveryNoteResource;
use App\Http\Resources\DeliveryNoteListResource;
use App\Models\DeliveryNote;
use Illuminate\Http\JsonResponse;

class DeliveryNoteController extends Controller
{
    public function __construct() {
        //
    }

    /**
     * Display a listing of delivery notes.
     */
    public function index(): JsonResponse
    {
        $deliveryNotes = DeliveryNote::query()
            ->withItemsCount()
            ->search(request('search'))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

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
        $deliveryNote = DeliveryNote::createWithItems($request->validated());

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
        $deliveryNote->load(['items', 'customer']);

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
        $deliveryNote = $deliveryNote->updateWithItems($request->validated());

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
        $deliveryNote->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delivery note deleted successfully',
        ]);
    }
}
