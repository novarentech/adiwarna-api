<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDocumentTransmittalRequest;
use App\Http\Requests\Api\V1\UpdateDocumentTransmittalRequest;
use App\Http\Resources\V1\DocumentTransmittalCollection;
use App\Http\Resources\V1\DocumentTransmittalResource;
use App\Models\DocumentTransmittal;
use App\Services\DocumentTransmittalService;
use Illuminate\Http\JsonResponse;

class DocumentTransmittalController extends Controller
{
    public function __construct(
        private readonly DocumentTransmittalService $documentTransmittalService
    ) {
        $this->authorizeResource(DocumentTransmittal::class, 'document_transmittal');
    }

    /**
     * Display a listing of document transmittals.
     */
    public function index(): JsonResponse
    {
        $transmittals = $this->documentTransmittalService->getPaginatedTransmittals(
            perPage: request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => new DocumentTransmittalCollection($transmittals),
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
        $transmittal = $this->documentTransmittalService->createTransmittalWithDocuments($request->validated());

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
        $transmittal = $this->documentTransmittalService->updateTransmittalWithDocuments(
            $documentTransmittal->id,
            $request->validated()
        );

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
        $this->documentTransmittalService->deleteTransmittal($documentTransmittal->id);

        return response()->json([
            'success' => true,
            'message' => 'Document transmittal deleted successfully',
        ]);
    }
}
