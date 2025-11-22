<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreQuotationRequest;
use App\Http\Requests\Api\V1\UpdateQuotationRequest;
use App\Http\Resources\V1\QuotationListResource;
use App\Http\Resources\V1\QuotationResource;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    public function __construct(
        private readonly QuotationService $quotationService
    ) {
        $this->authorizeResource(Quotation::class, 'quotation');
    }

    public function index(): JsonResponse
    {
        $quotations = $this->quotationService->getPaginatedQuotations(
            perPage: request('per_page', 15),
            search: request('search')
        );

        return response()->json([
            'success' => true,
            'data' => QuotationListResource::collection($quotations),
            'meta' => [
                'current_page' => $quotations->currentPage(),
                'last_page' => $quotations->lastPage(),
                'per_page' => $quotations->perPage(),
                'total' => $quotations->total(),
            ],
        ]);
    }

    public function store(StoreQuotationRequest $request): JsonResponse
    {
        $quotation = $this->quotationService->createQuotation($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Quotation created successfully',
            'data' => new QuotationResource($quotation),
        ], 201);
    }

    public function show(Quotation $quotation): JsonResponse
    {
        $quotation->load(['customer', 'items', 'adiwarnas', 'clients']);

        return response()->json([
            'success' => true,
            'data' => new QuotationResource($quotation),
        ]);
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation): JsonResponse
    {
        $quotation = $this->quotationService->updateQuotation(
            $quotation->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Quotation updated successfully',
            'data' => new QuotationResource($quotation),
        ]);
    }

    public function destroy(Quotation $quotation): JsonResponse
    {
        $this->quotationService->deleteQuotation($quotation->id);

        return response()->json([
            'success' => true,
            'message' => 'Quotation deleted successfully',
        ]);
    }
}
