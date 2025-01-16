<?php
namespace App\Services;
namespace App\Services;

use App\Jobs\FinetuneJob;
use App\Models\FinetuneJob as FinetuneJobModel;
use Illuminate\Support\Facades\Log;

class FinetuneService
{
    public function startFinetune(string $modelId, array $trainingData, array $parameters = []): string
    {
        $job = FinetuneJobModel::create([
            'model_id' => $modelId,
            'status' => 'pending',
            'parameters' => $parameters
        ]);

        $formattedData = $this->formatTrainingData($trainingData);
        $this->dispatch($job->id, $modelId, $formattedData, $parameters);

        return $job->id;
    }

    private function formatTrainingData(array $data): array
    {
        return array_map(fn($item) => [
            'prompt' => $item['input'],
            'completion' => $item['output']
        ], $data);
    }

    private function dispatch(string $jobId, string $modelId, array $data, array $parameters): void
    {
        try {
            FinetuneJob::dispatch($jobId, $modelId, $parameters);
        } catch (\Exception $e) {
            Log::error('Finetune job dispatch failed: ' . $e->getMessage());
            throw new \RuntimeException('Failed to start finetune job', 0, $e);
        }
    }
}