<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Http\Resources\QuotationListResource;
use App\Http\Resources\QuotationResource;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    public function __construct() {
        //
    }

    public function index(): JsonResponse
    {
        $quotations = Quotation::query()
            ->with(['customer'])
            ->search(request('search'))
            ->when(request('customer_id'), fn($q) => $q->filterByCustomer(request('customer_id')))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

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
        $quotation = Quotation::createWithItems($request->validated());

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
        $quotation->updateWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Quotation updated successfully',
            'data' => new QuotationResource($quotation),
        ]);
    }

    public function destroy(Quotation $quotation): JsonResponse
    {
        $quotation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quotation deleted successfully',
        ]);
    }
}
