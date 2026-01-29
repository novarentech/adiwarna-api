<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialReceivingReportRequest;
use App\Http\Requests\UpdateMaterialReceivingReportRequest;
use App\Http\Resources\MaterialReceivingReportCollection;
use App\Http\Resources\MaterialReceivingReportResource;
use App\Http\Resources\MaterialReceivingReportListResource;
use App\Models\MaterialReceivingReport;
use Illuminate\Http\JsonResponse;

class MaterialReceivingReportController extends Controller
{
    public function __construct() {
        //
    }

    /**
     * Display a listing of material receiving reports.
     */
    public function index(): JsonResponse
    {
        $reports = MaterialReceivingReport::query()
            ->withItemsCount()
            ->search(request('search'))
            ->when(request('start_date') && request('end_date'), fn($q) => $q->filterByDateRange(request('start_date'), request('end_date')))
            ->sortDefault(request('sort_order', 'desc'))
            ->paginate(request('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => MaterialReceivingReportListResource::collection($reports),
            'meta' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
        ]);
    }

    /**
     * Store a newly created material receiving report.
     */
    public function store(StoreMaterialReceivingReportRequest $request): JsonResponse
    {
        $report = MaterialReceivingReport::createWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Material receiving report created successfully',
            'data' => new MaterialReceivingReportResource($report),
        ], 201);
    }

    /**
     * Display the specified material receiving report.
     */
    public function show(MaterialReceivingReport $materialReceivingReport): JsonResponse
    {
        $materialReceivingReport->load('items');

        return response()->json([
            'success' => true,
            'data' => new MaterialReceivingReportResource($materialReceivingReport),
        ]);
    }

    /**
     * Update the specified material receiving report.
     */
    public function update(UpdateMaterialReceivingReportRequest $request, MaterialReceivingReport $materialReceivingReport): JsonResponse
    {
        $report = $materialReceivingReport->updateWithItems($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Material receiving report updated successfully',
            'data' => new MaterialReceivingReportResource($report),
        ]);
    }

    /**
     * Remove the specified material receiving report.
     */
    public function destroy(MaterialReceivingReport $materialReceivingReport): JsonResponse
    {
        $materialReceivingReport->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material receiving report deleted successfully',
        ]);
    }
}
