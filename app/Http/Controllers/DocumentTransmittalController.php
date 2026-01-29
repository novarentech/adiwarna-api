<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentTransmittalRequest;
use App\Http\Requests\UpdateDocumentTransmittalRequest;
use App\Http\Resources\DocumentTransmittalResource;
use App\Models\DocumentTransmittal;
use Illuminate\Http\JsonResponse;

class DocumentTransmittalController extends Controller
{
    public function __construct() {
        //
    }

    /**
     * Display a listing of document transmittals.
     * Supports search by customer name or PIC name via ?search= query parameter.
     */
    public function index(): JsonResponse
    {
        $transmittals = DocumentTransmittal::query()
            ->withCustomerOnly()
            ->search(request('search'))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => DocumentTransmittalResource::collection($transmittals),
            'meta' => [
                'current_page' => $transmittals->currentPage(),
                'last_page' => $transmittals->lastPage(),
                'per_page' => $transmittals->perPage(),
                'total' => $transmittals->total(),
            ],
        ]);
    }

    /**
     * Store a newly created document transmittal.
     */
    public function store(StoreDocumentTransmittalRequest $request): JsonResponse
    {
        $transmittal = DocumentTransmittal::createWithDocuments($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Document transmittal created successfully',
            'data' => new DocumentTransmittalResource($transmittal),
        ], 201);
    }

    /**
     * Display the specified document transmittal.
     */
    public function show(DocumentTransmittal $documentTransmittal): JsonResponse
    {
        $documentTransmittal->load(['customer', 'documents']);

        return response()->json([
            'success' => true,
            'data' => new DocumentTransmittalResource($documentTransmittal),
        ]);
    }

    /**
     * Update the specified document transmittal.
     */
    public function update(UpdateDocumentTransmittalRequest $request, DocumentTransmittal $documentTransmittal): JsonResponse
    {
        $transmittal = $documentTransmittal->updateWithDocuments($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Document transmittal updated successfully',
            'data' => new DocumentTransmittalResource($transmittal),
        ]);
    }

    /**
     * Remove the specified document transmittal.
     */
    public function destroy(DocumentTransmittal $documentTransmittal): JsonResponse
    {
        $documentTransmittal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document transmittal deleted successfully',
        ]);
    }
}
