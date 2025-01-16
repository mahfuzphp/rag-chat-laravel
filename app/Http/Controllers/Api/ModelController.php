<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\ModelService;
use Illuminate\Http\JsonResponse;



class ModelController extends Controller
{
    public function __construct(
        private readonly ModelService $modelService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'models' => $this->modelService->getAvailableModels()
        ]);
    }

    public function show(string $modelId): JsonResponse
    {
        return response()->json([
            'model' => $this->modelService->getModelDetails($modelId)
        ]);
    }
}
