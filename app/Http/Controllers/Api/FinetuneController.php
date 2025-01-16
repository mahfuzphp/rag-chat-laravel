<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinetuneRequest;
use App\Services\FinetuneService;
use Illuminate\Http\JsonResponse;


class FinetuneController extends Controller
{
    public function __construct(
        private readonly FinetuneService $finetuneService
    ) {}

    public function start(FinetuneRequest $request): JsonResponse
    {
        $jobId = $this->finetuneService->startFinetune(
            $request->input('model_id'),
            $request->input('training_data'),
            $request->input('parameters', [])
        );

        return response()->json([
            'message' => 'Finetuning job started',
            'job_id' => $jobId
        ]);
    }

    public function status(string $jobId): JsonResponse
    {
        return response()->json([
            'status' => $this->finetuneService->getJobStatus($jobId)
        ]);
    }
}
