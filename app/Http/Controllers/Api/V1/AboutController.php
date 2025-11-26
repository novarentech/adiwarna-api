<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAboutRequest;
use App\Http\Requests\Api\V1\UpdateAboutRequest;
use App\Http\Resources\V1\AboutCollection;
use App\Http\Resources\V1\AboutResource;
use App\Models\About;
use App\Services\AboutService;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    public function __construct(
        private readonly AboutService $aboutService
    ) {
        $this->authorizeResource(About::class, 'about');
    }

    /**
     * Display a listing of company information.
     */
    public function index(): JsonResponse
    {
        $abouts = $this->aboutService->getAllAbout();

        return response()->json([
            'success' => true,
            'data' => new AboutCollection($abouts),
        ]);
    }

    /**
     * Store a newly created company information.
     */
    public function store(StoreAboutRequest $request): JsonResponse
    {
        $about = $this->aboutService->createAbout($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Company information created successfully',
            'data' => new AboutResource($about),
        ], 201);
    }

    /**
     * Display the specified company information.
     */
    public function show(About $about): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AboutResource($about),
        ]);
    }

    /**
     * Update the specified company information.
     */
    public function update(UpdateAboutRequest $request, About $about): JsonResponse
    {
        $about = $this->aboutService->updateAbout(
            $about->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Company information updated successfully',
            'data' => new AboutResource($about),
        ]);
    }

    /**
     * Remove the specified company information.
     */
    public function destroy(About $about): JsonResponse
    {
        $this->aboutService->deleteAbout($about->id);

        return response()->json([
            'success' => true,
            'message' => 'Company information deleted successfully',
        ]);
    }

    /**
     * Get the active company information.
     */
    public function active(): JsonResponse
    {
        $about = $this->aboutService->getActiveAbout();

        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'No active company information found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AboutResource($about),
        ]);
    }
}
